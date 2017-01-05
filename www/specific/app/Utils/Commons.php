<?php

namespace App\Utils;

use App\Exceptions\AnnotateException;
use App\Exceptions\CommandException;
use App\Exceptions\ExportSubStructuresException;
use App\Models\Disease;
use App\Models\Node;

final class Commons
{
    const R_EXEC = 'Rscript %1$s %2$s';
    const R_MAKE_HEATMAP = 'bin/R/makeHeatmap.R';
    const R_MAKE_HEATMAP_PARAMETERS = '-c %1$s -t %2$s -s %3$s -o %4$s';
    const R_ANNOTATE = 'bin/R/annotate.R';
    const R_ANNOTATE_PARAMETERS = '-l %1$s -p %2$f -a %3$s -o %4$s';
    const EXCLUDED_PATHWAY_CATEGORIES = [
        'Endocrine and metabolic diseases',
        'Neurodegenerative diseases',
        'Human Diseases',
        'Immune diseases',
        'Infectious diseases',
        'Cardiovascular diseases'
    ];
    const MITHRIL_EXPORT_PARAMETERS = '-b -verbose -organism hsa -i %1$s -o %2$s -s %3$s' .
                                      ' -max-pvalue %4$f -min-number-of-nodes %5$d -combiner %6$s %7$s' .
                                      ' -exclude-categories %8$s';
    const MITHRIL_JAR = 'bin/MITHrIL2.jar';
    const MITHRIL_EXEC = 'java -jar %1$s %2$s %3$s';

    /**
     * Get name for control data of a disease
     *
     * @param Disease $disease
     * @return string
     */
    public static function getDiseaseControl(Disease $disease)
    {
        $diseaseName = $disease->short_name;
        $diseaseArray = explode(' ', $diseaseName);
        array_pop($diseaseArray);
        $diseaseArray[] = 'N';
        return implode(' ', $diseaseArray);
    }

    /**
     * Get the path of the expression data of a disease or its control
     *
     * @param Disease $disease
     * @param bool    $control
     * @return string
     */
    public static function getExpressionPath(Disease $disease, $control = false)
    {
        $name = str_replace(' ', '_', (($control) ? self::getDiseaseControl($disease) : $disease->short_name));
        return resource_path('data/expressions/' . $name . '.txt.gz');
    }

    /**
     * Get thr path of the MITHrIL 2 input file for a disease
     *
     * @param Disease $disease
     * @return string
     */
    public static function getMithrilInputPath(Disease $disease)
    {
        $name = str_replace(' ', '_', $disease->short_name);
        return resource_path('data/mithril2/' . $name . '-N.results.datz');
    }

    /**
     * Generate an heatmap
     *
     * @param Disease    $disease
     * @param array      $selection
     * @param array|null $commandOutput
     * @return string
     */
    public static function makeHeatmap(Disease $disease, array $selection, array &$commandOutput = null)
    {
        $key = md5($disease->short_name . '-' . implode('-', $selection));
        $heatmapFile = Utils::getStorageDirectory('heatmaps') . DIRECTORY_SEPARATOR . $key . '.png';
        if (file_exists($heatmapFile)) {
            return $heatmapFile;
        }
        $diseasePath = escapeshellarg(self::getExpressionPath($disease));
        $controlPath = escapeshellarg(self::getExpressionPath($disease, true));
        $selectionString = escapeshellarg(implode(',', $selection));
        $paramString = sprintf(self::R_MAKE_HEATMAP, $diseasePath, $controlPath, $selectionString,
            escapeshellarg($heatmapFile));
        $command = sprintf(self::R_EXEC, resource_path(self::R_MAKE_HEATMAP), $paramString);
        Utils::runCommand($command, $commandOutput);
        return $heatmapFile;
    }

    /**
     * Annotate a set of nodes and returns the output file name
     *
     * @param array  $list
     * @param float  $maxPValue
     * @param string $pvAdjust
     * @return string
     * @throws AnnotateException
     */
    public static function makeAnnotation(array $list, $maxPValue = 0.05, $pvAdjust = "BH")
    {
        if (!count($list)) {
            throw new AnnotateException('List of nodes to annotate is empty.');
        }
        $list = array_map(function ($e) {
            if ($e instanceof Node) {
                return $e->accession;
            }
            return $e;
        }, $list);
        sort($list);
        $key = Utils::makeKey($list, $maxPValue, $pvAdjust);
        $outputFile = Utils::getStorageDirectory('annotations') . DIRECTORY_SEPARATOR . $key . '.txt';
        if (!file_exists($outputFile)) {
            $tempFile = Utils::tempFile('list', 'txt');
            file_put_contents($tempFile, implode("\n", $list) . PHP_EOL);
            $paramString = sprintf(self::R_ANNOTATE_PARAMETERS, escapeshellarg($tempFile), $maxPValue,
                escapeshellarg($pvAdjust), escapeshellarg($outputFile));
            $command = sprintf(self::R_EXEC, resource_path(self::R_ANNOTATE), $paramString);
            $commandOutput = [];
            try {
                Utils::runCommand($command, $commandOutput);
            } catch (CommandException $e) {
                $code = intval($e->getMessage());
                if ($code == 102) {
                    throw new AnnotateException(array_pop($commandOutput));
                } else {
                    throw new AnnotateException('Error ' . $code . ' during annotation.');
                }
            } finally {
                unlink($tempFile);
            }
        }
        return [$key, $outputFile];
    }

    /**
     * Run MITHrIL 2 to extract SubStructures
     *
     * @param Disease    $disease
     * @param string     $outputFile
     * @param array      $nodesOfInterest
     * @param float      $maxPValue
     * @param int        $minNumberOfNodes
     * @param string     $combiner
     * @param bool       $backward
     * @param array|null $commandOutput
     * @return bool
     */
    public static function exportSubStructures(Disease $disease, $outputFile, array $nodesOfInterest,
        $maxPValue = 0.05, $minNumberOfNodes = 5, $combiner = "fisher", $backward = false, array &$commandOutput = null)
    {
        if (!count($nodesOfInterest)) {
            throw new ExportSubStructuresException('List of nodes of interest is empty.');
        }
        $nodesOfInterest = array_map(function ($e) {
            if ($e instanceof Node) {
                return $e->accession;
            }
            return $e;
        }, $nodesOfInterest);
        $inputFile = escapeshellarg(self::getMithrilInputPath($disease));
        $nodesOfInterest = escapeshellarg(implode(',', $nodesOfInterest));
        $outputFile = escapeshellarg($outputFile);
        $combiner = escapeshellarg($combiner);
        $backward = ($backward) ? '-backward' : '';
        $excluded = escapeshellarg(implode(',', self::EXCLUDED_PATHWAY_CATEGORIES));
        $parameters = sprintf(self::MITHRIL_EXPORT_PARAMETERS, $inputFile, $outputFile, $nodesOfInterest, $maxPValue,
            $minNumberOfNodes, $combiner, $backward, $excluded);
        $command = sprintf(self::MITHRIL_EXEC, resource_path(self::MITHRIL_JAR), 'exportstructs', $parameters);
        return Utils::runCommand($command, $commandOutput);
    }

}