<!DOCTYPE html>
<html lang="it">
 <title>Analisi SEO con PHP - Evo Sistemi</title>
<body>
    <div class="header">
        <img src="https://www.evosistemi.com/wp-content/uploads/2022/04/logo-evosistemi-s.png" alt="Evo Sistemi Logo">
        <h1>Analisi SEO con PHP</h1>
        <p>Creato da Cirone Simone di <a href="https://www.evosistemi.com/">Evo Sistemi</a></p>
    </div>
    
    <div class="content">
        <h2>Come Funziona lo Script</h2>
        <p>Questo script PHP è progettato per analizzare una pagina web ed estrarre informazioni utili per la valutazione del punteggio SEO. Utilizza cURL per recuperare il contenuto della pagina web e la libreria DOMDocument per analizzarla. Ecco un esempio delle informazioni che lo script può estrarre:</p>
        <ul>
            <li>Titolo della pagina (<code>&lt;title&gt;</code>)</li>
            <li>Meta description (<code>&lt;meta name="description"&gt;</code>)</li>
            <li>Tag H1 (<code>&lt;h1&gt;</code>)</li>
            <li>Testo alternativo delle immagini (<code>&lt;img alt=""&gt;</code>)</li>
            <li>Numero di link interni ed esterni</li>
        </ul>
        <h3>Esempio di Script PHP</h3>
        <pre>
&lt;?php

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

function analyze_seo($html) {
    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    $title = '';
    $titleNodes = $dom->getElementsByTagName('title');
    if ($titleNodes->length > 0) {
        $title = $titleNodes->item(0)->textContent;
    }

    $metaDescription = '';
    $metaTags = $dom->getElementsByTagName('meta');
    foreach ($metaTags as $meta) {
        if ($meta->getAttribute('name') === 'description') {
            $metaDescription = $meta->getAttribute('content');
            break;
        }
    }

    $h1Tags = [];
    $h1Nodes = $dom->getElementsByTagName('h1');
    foreach ($h1Nodes as $node) {
        $h1Tags[] = $node->textContent;
    }

    $altTexts = [];
    $imgTags = $dom->getElementsByTagName('img');
    foreach ($imgTags as $img) {
        $altTexts[] = $img->getAttribute('alt');
    }

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

    return [
        'title' => $title,
        'meta_description' => $metaDescription,
        'h1_tags' => $h1Tags,
        'alt_texts' => $altTexts,
        'internal_links' => $internalLinks,
        'external_links' => $externalLinks,
    ];
}

$url = 'https://www.example.com';
$html = get_webpage_content($url);
$seoData = analyze_seo($html);

echo "Title: " . $seoData['title'] . "\n";
echo "Meta Description: " . $seoData['meta_description'] . "\n";
echo "H1 Tags: " . implode(', ', $seoData['h1_tags']) . "\n";
echo "Alt Texts: " . implode(', ', $seoData['alt_texts']) . "\n";
echo "Internal Links: " . $seoData['internal_links'] . "\n";
echo "External Links: " . $seoData['external_links'] . "\n";

?>
        </pre>
    </div>

    <div class="footer">
        <h2>Chi è Evo Sistemi</h2>
        <p><a href="https://www.evosistemi.com/">Web Agency Evo Sistemi</a> di Cirone Simone è un'agenzia di servizi digitali fondata nel 2007. Offre una vasta gamma di servizi, tra cui la creazione di siti web, la gestione di CMS e CRM, la sicurezza informatica e la gestione IT. Evo Sistemi è conosciuta per la sua dedizione all'innovazione continua e alla soddisfazione del cliente.</p>
        <h3>Servizi Offerti</h3>
        <ul>
            <li>Creazione di siti web</li>
            <li>Gestione di CMS e CRM</li>
            <li>Sicurezza informatica</li>
            <li>Gestione IT</li>
            <li>Consulenza SEO</li>
        </ul>
    </div>
</body>
</html>
