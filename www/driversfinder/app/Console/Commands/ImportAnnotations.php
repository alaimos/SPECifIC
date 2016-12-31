<?php

namespace App\Console\Commands;

use App\Models\AnnotationSource;
use App\Models\AnnotationTerm;
use App\Models\Node;
use Illuminate\Console\Command;
use phpDocumentor\Reflection\DocBlock\Tags\Source;

class ImportAnnotations extends Command
{

    /**
     * @var array
     */
    private $nodesMap;

    /**
     * @var array
     */
    private $sourceMap = [];

    /**
     * @var AnnotationTerm[]
     */
    private $termsMap = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:annotations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all nodes annotations';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create and Store AnnotationTerm model object
     *
     * @param string $id
     * @param string $text
     * @param string $source
     * @return AnnotationTerm
     */
    private function makeTerm($id, $text, $source)
    {
        if (strlen($id) > 255) {
            $id = substr($id, 0, 255);
        }
        if (!isset($this->termsMap[$source][$id])) {
            $term = AnnotationTerm::create([
                'accession'   => $id,
                'description' => $text,
                'source_id'   => $source,
            ]);
            $this->termsMap[$source][$id] = $term;
        }
        return $this->termsMap[$source][$id];
    }

    /**
     * Create and Store AnnotationSource model object
     *
     * @param string $id
     * @param string $name
     * @return string
     */
    private function makeSource($id, $name)
    {
        if (!isset($this->sourceMap[$id])) {
            AnnotationSource::create([
                'id'   => $id,
                'name' => $name,
            ]);
            $this->sourceMap[$id] = $id;
            $this->termsMap[$id] = [];
        }
        return $this->sourceMap[$id];
    }

    /**
     * Annotate a gene
     *
     * @param string         $gentId
     * @param AnnotationTerm $term
     */
    private function annotate($gentId, AnnotationTerm $term)
    {
        $id = $this->nodesMap[$gentId];
        if ($term->annotatedNodes()->find($id) === null) {
            $term->annotatedNodes()->attach($id);
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Building Nodes Map");
        $this->nodesMap = Node::all()->pluck('id', 'accession');
        $this->info("Importing annotations");
        $annotationFile = resource_path('data/annotation.txt.gz');
        $totalLines = intval(exec('zcat ' . escapeshellarg($annotationFile) . ' | wc -l'));
        $fp = gzopen($annotationFile, 'r');
        if (!$fp) {
            $this->error('Unable to read diseases file');
            return 101;
        } else {
            $bar = $this->output->createProgressBar($totalLines);
            fgets($fp); //ignore first line
            $bar->advance();
            while (($line = fgets($fp)) !== false) {
                $line = str_replace(["\r", "\n"], "", $line);
                if (strlen($line) == 0 || $line{0} == '#') {
                    $bar->advance();
                    continue;
                }
                $fields = explode("\t", $line);
                if (count($fields) == 5) {
                    if (isset($this->nodesMap[$fields[0]])) {
                        $source = $this->makeSource($fields[3], $fields[4]);
                        $term = $this->makeTerm($fields[1], $fields[2], $source);
                        $this->annotate($fields[0], $term);
                    }
                }
                $bar->advance();
            }
            $bar->finish();
            $this->info("\nDone!");
        }
        return 0;
    }
}
