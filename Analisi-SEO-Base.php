<?php
// Script php che utilizza cURL per recuperare 
// il contenuto di una pagina web e la libreria DOMDocument 
// per analizzarla e estrarre alcune informazioni SEO di base.
// Creato da Simone Cirone https://www.evosistemi.com
// Funzione per recuperare il contenuto HTML di una pagina web
function get_webpage_content($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

// Funzione per estrarre dati SEO dalla pagina HTML
function analyze_seo($html) {
    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    // Estrai il titolo della pagina
    $title = '';
    $titleNodes = $dom->getElementsByTagName('title');
    if ($titleNodes->length > 0) {
        $title = $titleNodes->item(0)->textContent;
    }

    // Estrai la meta description
    $metaDescription = '';
    $metaTags = $dom->getElementsByTagName('meta');
    foreach ($metaTags as $meta) {
        if ($meta->getAttribute('name') === 'description') {
            $metaDescription = $meta->getAttribute('content');
            break;
        }
    }

    // Estrai i tag H1
    $h1Tags = [];
    $h1Nodes = $dom->getElementsByTagName('h1');
    foreach ($h1Nodes as $node) {
        $h1Tags[] = $node->textContent;
    }

    // Estrai il testo alternativo delle immagini
    $altTexts = [];
    $imgTags = $dom->getElementsByTagName('img');
    foreach ($imgTags as $img) {
        $altTexts[] = $img->getAttribute('alt');
    }

    // Conta i link interni ed esterni
    $internalLinks = 0;
    $externalLinks = 0;
    $aTags = $dom->getElementsByTagName('a');
    foreach ($aTags as $a) {
        $href = $a->getAttribute('href');
        if (strpos($href, 'http') === 0) {
            if (strpos($href, $_SERVER['SERVER_NAME']) !== false) {
                $internalLinks++;
            } else {
                $externalLinks++;
            }
        }
    }

    // Restituisci i risultati come array associativo
    return [
        'title' => $title,
        'meta_description' => $metaDescription,
        'h1_tags' => $h1Tags,
        'alt_texts' => $altTexts,
        'internal_links' => $internalLinks,
        'external_links' => $externalLinks,
    ];
}

// Esempio di utilizzo
$url = 'https://www.example.com';
$html = get_webpage_content($url);
$seoData = analyze_seo($html);

// Stampa i risultati
echo "Title: " . $seoData['title'] . "\n";
echo "Meta Description: " . $seoData['meta_description'] . "\n";
echo "H1 Tags: " . implode(', ', $seoData['h1_tags']) . "\n";
echo "Alt Texts: " . implode(', ', $seoData['alt_texts']) . "\n";
echo "Internal Links: " . $seoData['internal_links'] . "\n";
echo "External Links: " . $seoData['external_links'] . "\n";

?>
