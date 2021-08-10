<?php

namespace CidiLabs\PhpAlly;

use DOMDocument;

class PhpAlly {
    public function __construct()
    {

    }

    public function checkOne($content, $ruleId, $options = [])
    {
        return $this->checkMany($content, [$ruleId], $options);
    }

    public function checkMany($content, $ruleIds = [], $options = [])
    {
        $report = new PhpAllyReport();
        $document = $this->getDomDocument($content);

        foreach ($ruleIds as $ruleId) {
            try {
                $className = $ruleId;
                if (!class_exists($className)) {
                    $report->setError('Rule does not exist.');
                    continue;
                }           

                $rule = new $className($document, $options);
                $rule->check();                

                $report->setIssues($rule->getIssues());
                $report->setErrors($rule->getErrors());
            } catch (\Exception $e) {
                print($e->getLine());
                $report->setError($e->getMessage());
            }
        }

        return $report;
    }

    public function getDomDocument($html)
    {
        $dom = new DOMDocument('1.0', 'utf-8');
        if (strpos($html, '<?xml encoding="utf-8"') !== false) {
            $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        } else {
            $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        }

        return $dom;
    }

    public function getRuleIds()
    {
        $path = __DIR__ . '/rules.json';
        $json = file_get_contents($path);

        return \json_decode($json, true);
    }
}