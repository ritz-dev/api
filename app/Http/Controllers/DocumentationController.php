<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
class DocumentationController extends Controller
{
    private $docsPath;

    public function __construct()
    {
        $this->docsPath = base_path('docs_root');
    }

    public function index()
    {
        // Get list of documentation files
        $docFiles = [
            'README.md' => 'Main README',
            'DOC_INDEX.md' => 'Documentation Index',
            'QUICK_START_GUIDE.md' => 'Quick Start Guide',
            'TROUBLESHOOTING_GUIDE.md' => 'Troubleshooting Guide',
            'ADVANCED_CONFIGURATION.md' => 'Advanced Configuration',
            'K8S_DEPLOYMENT_GUIDE.md' => 'Kubernetes Deployment Guide',
            'KUBECONFIG_SETUP_README.md' => 'Kubeconfig Setup',
        ];

        $docsFromFolder = [
            'CI-CD-Developer-Guide.md' => 'CI/CD Developer Guide',
            'DevOps-Kubernetes-Guide.md' => 'DevOps Kubernetes Guide',
            'GitHub-to-Kubernetes-Deployment-Guide.md' => 'GitHub to K8s Deployment',
            'Kubernetes-Developer-Guide.md' => 'Kubernetes Developer Guide',
            'Laravel-Kubernetes-Troubleshooting-Guide.md' => 'Laravel K8s Troubleshooting',
        ];

        return view('documentation.index', [
            'docFiles' => $docFiles,
            'docsFromFolder' => $docsFromFolder
        ]);
    }

    public function show($filename)
    {
        $filePath = $this->docsPath . '/' . $filename;
        
        // Security check - prevent directory traversal
        if (strpos($filename, '..') !== false || !File::exists($filePath)) {
            abort(404, 'Documentation file not found');
        }

        $content = File::get($filePath);
        $htmlContent = $this->markdownToHtml($content);
        
        return view('documentation.show', [
            'filename' => $filename,
            'title' => $this->getFileTitle($filename),
            'content' => $htmlContent,
            'rawContent' => $content
        ]);
    }

    private function getFileTitle($filename)
    {
        $titles = [
            'README.md' => 'SMS Development System - Main Documentation',
            'DOC_INDEX.md' => 'Documentation Index',
            'QUICK_START_GUIDE.md' => 'Quick Start Guide',
            'TROUBLESHOOTING_GUIDE.md' => 'Troubleshooting Guide',
            'ADVANCED_CONFIGURATION.md' => 'Advanced Configuration',
            'K8S_DEPLOYMENT_GUIDE.md' => 'Kubernetes Deployment Guide',
            'KUBECONFIG_SETUP_README.md' => 'Kubeconfig Setup README',
            'CI-CD-Developer-Guide.md' => 'CI/CD Developer Guide',
            'DevOps-Kubernetes-Guide.md' => 'DevOps Kubernetes Guide',
            'GitHub-to-Kubernetes-Deployment-Guide.md' => 'GitHub to Kubernetes Deployment Guide',
            'Kubernetes-Developer-Guide.md' => 'Kubernetes Developer Guide',
            'Laravel-Kubernetes-Troubleshooting-Guide.md' => 'Laravel Kubernetes Troubleshooting Guide',
        ];

        return $titles[$filename] ?? ucfirst(str_replace(['.md', '-'], [' Documentation', ' '], basename($filename)));
    }

    private function markdownToHtml($markdown)
    {
        // Simple markdown to HTML converter
        $html = $markdown;
        
        // Headers
        $html = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $html);
        $html = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $html);
        $html = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $html);
        $html = preg_replace('/^#### (.+)$/m', '<h4>$1</h4>', $html);
        
        // Bold and italic
        $html = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html);
        $html = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $html);
        
        // Links
        $html = preg_replace('/\[(.+?)\]\((.+?)\)/', '<a href="$2">$1</a>', $html);
        
        // Code blocks
        $html = preg_replace('/```(\w+)?\n(.*?)\n```/s', '<pre><code>$2</code></pre>', $html);
        $html = preg_replace('/`(.+?)`/', '<code>$1</code>', $html);
        
        // Lists
        $html = preg_replace('/^[\*\-\+] (.+)$/m', '<li>$1</li>', $html);
        $html = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $html);
        
        // Line breaks
        $html = preg_replace('/\n\n/', '</p><p>', $html);
        $html = '<p>' . $html . '</p>';
        
        // Clean up empty paragraphs
        $html = preg_replace('/<p><\/p>/', '', $html);
        $html = preg_replace('/<p>(<h[1-6]>)/', '$1', $html);
        $html = preg_replace('/(<\/h[1-6]>)<\/p>/', '$1', $html);
        $html = preg_replace('/<p>(<ul>)/', '$1', $html);
        $html = preg_replace('/(<\/ul>)<\/p>/', '$1', $html);
        $html = preg_replace('/<p>(<pre>)/', '$1', $html);
        $html = preg_replace('/(<\/pre>)<\/p>/', '$1', $html);
        
        return $html;
    }
}