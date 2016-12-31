<?php

namespace App\Console\Commands;

use App\Models\Disease;
use App\Models\Node;
use Illuminate\Console\Command;

class ImportDiseases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:diseases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all diseases data in the database';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function importPerturbations(Disease $disease, $file, &$nodesMap)
    {
        $fp = gzopen($file, 'r');
        if (!$fp) {
            throw new \RuntimeException("Unable to read perturbation file (" . $file . ")");
        }
        $bar = $this->output->createProgressBar(count($nodesMap));
        while (($line = fgets($fp)) !== false) {
            $line = str_replace(["\r", "\n"], "", $line);
            if (strlen($line) == 0 || $line{0} == '#') {
                continue;
            }
            $fields = explode("\t", $line);
            if (count($fields) == 6) {
                $geneAccession = $fields[2];
                if (isset($nodesMap[$geneAccession])) {
                    $geneId = $nodesMap[$geneAccession];
                    $perturbation = doubleval($fields[4]);
                    if (!$disease->perturbations()->find($geneId) && $perturbation != 0.0) {
                        $pv = doubleval($fields[5]);
                        $disease->perturbations()->attach($geneId, [
                            'perturbation' => $perturbation,
                            'pvalue'       => $pv,
                        ]);
                        $bar->advance();
                    }
                }
            }
        }
        $bar->finish();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Building Nodes Map");
        $nodesMap = Node::all()->pluck('id', 'accession');
        $this->info("Importing diseases");
        $diseasesFile = resource_path('data/diseases.txt.gz');
        $mithrilOutput = resource_path('data/mithril2/%s-N.nodes.txt.gz');
        $fp = gzopen($diseasesFile, 'r');
        if (!$fp) {
            $this->error('Unable to read diseases file');
            return 101;
        } else {
            while (($line = fgets($fp)) !== false) {
                $line = str_replace(["\r", "\n"], "", $line);
                if (strlen($line) == 0 || $line{0} == '#') {
                    continue;
                }
                $fields = explode("\t", $line);
                if (count($fields) == 2) {
                    $disease = Disease::create([
                        'short_name'  => $fields[0],
                        'description' => $fields[1],
                    ]);
                    $perturbationsFile = sprintf($mithrilOutput, str_replace(' ', '_', $fields[0]));
                    $this->importPerturbations($disease, $perturbationsFile, $nodesMap);
                }
            }
            $this->info("Done!");
        }
        return 0;
    }
}
