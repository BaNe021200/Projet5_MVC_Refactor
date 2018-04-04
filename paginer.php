<?php
declare(strict_types=1);
function paginer($nb_results, $nb_results_p_page, $numero_page_courante, $nb_avant, $nb_apres, $premiere, $derniere)
{
    // Initialisation de la variable a retourner
    $resultat = '';

    // nombre total de pages
    $nb_pages = ceil($nb_results / $nb_results_p_page);
    // nombre de pages avant
    $avant = $numero_page_courante > ($nb_avant + 1) ? $nb_avant : $numero_page_courante - 1;
    // nombre de pages apres
    $apres = $numero_page_courante <= $nb_pages - $nb_apres ? $nb_apres : $nb_pages - $numero_page_courante;

    // premiere page
    if ($premiere && $numero_page_courante - $avant > 1) {
        $resultat .= '<a href="' . htmlspecialchars($_SERVER['PHP_SELF']) . '?numeroPage=1" title="Première page">&laquo;&laquo;</a>&nbsp;';
    }

    // page precedente
    if ($numero_page_courante > 1) {
        $resultat .= '<a href="' . htmlspecialchars($_SERVER['PHP_SELF']) . '?numeroPage=' . ($numero_page_courante - 1) . '" title="Page précédente ' . ($numero_page_courante - 1) . '">&laquo;</a>&nbsp;';
    }

    // affichage des numeros de page
    for ($i = $numero_page_courante - $avant; $i <= $numero_page_courante + $apres; $i++) {
        // page courante
        if ($i == $numero_page_courante) {
            $resultat .= '&nbsp;[<strong>' . $i . '</strong>]&nbsp;';
        } else {
            $resultat .= '&nbsp;[<a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES) . '?numeroPage=' . $i . '" title="Consulter la page ' . $i . '">' . $i . '</a>]&nbsp;';
        }
    }

    // page suivante
    if ($numero_page_courante < $nb_pages) {
        $resultat .= '<a href="' . htmlspecialchars($_SERVER['PHP_SELF']) . '?numeroPage=' . ($numero_page_courante + 1) . '" title="Consulter la page ' . ($numero_page_courante + 1) . ' !">&raquo;</a>&nbsp;';
    }

    // derniere page
    if ($derniere && ($numero_page_courante + $apres) < $nb_pages) {
        $resultat .= '<a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES) . '?numeroPage=' . $nb_pages . '" title="Dernière page">&raquo;&raquo;</a>&nbsp;';
    }

    // On retourne le resultat
    return $resultat;

}