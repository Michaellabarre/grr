<?php
/**
 * language.inc.php
 * Configuration de la langue
 * Ce script fait partie de l'application GRR
 * Derni�re modification : $Date: 2009-10-09 07:55:48 $
 * @author    Laurent Delineau <laurent.delineau@ac-poitiers.fr>
 * @copyright Copyright 2003-2008 Laurent Delineau
 * @link      http://www.gnu.org/licenses/licenses.html
 * @package   root
 * @version   $Id: language.inc.php,v 1.7 2009-10-09 07:55:48 grr Exp $
 * @filesource
 *
 * This file is part of GRR.
 *
 * GRR is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GRR is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GRR; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */
/**
 * $Log: language.inc.php,v $
 * Revision 1.7  2009-10-09 07:55:48  grr
 * *** empty log message ***
 *
 * Revision 1.6  2009-09-29 18:02:57  grr
 * *** empty log message ***
 *
 * Revision 1.5  2009-04-14 12:59:18  grr
 * *** empty log message ***
 *
 * Revision 1.4  2009-04-09 14:52:31  grr
 * *** empty log message ***
 *
 * Revision 1.3  2008-11-16 22:00:59  grr
 * *** empty log message ***
 *
 *
 */

#####################
# gestion des langues
#####################

# Les "locales" sont un syst�me permettant de g�rer le plus proprement possible les diff�rences de langue et de style
# des utilisateurs. Vous pouvez conna�tre les locales install�es sur votre syst�me linux avec la commande "locale -a"
# (les noms semblent peu standardis�s, vous aurez des cha�nes du genre "fr_FR" ou "fr_FR.ISO8859-1")

if (function_exists("getSettingValue")) {
    $defaultlanguage = getSettingValue("default_language");
} else {
    $defaultlanguage = false;
}

if (isset($_SESSION['default_language'])) {
    // si l'utilisateur a d�fini sa propre langue
    $locale = $_SESSION['default_language'];
} else if ($defaultlanguage) {
    // sinon, on utilise la variable stock�e dans la base
    $locale = $defaultlanguage;
} else {
    // sinon, on fixe la valeur � "fr"
    $locale = 'fr';
}

// $pass_leng est utilis� dans les fichiers langue, d'o� la ligne ci-dessous
$pass_leng = "";
if (isset($fichier_mysql_inc_est_present)) $pass_leng = grr_sql_query1("select VALUE from ".TABLE_PREFIX."_setting where NAME = 'pass_leng'");

// Fichier de traduction
if (@file_exists("language/lang." . $locale)) {
    $lang_file = "language/lang." . $locale;
} else {
    $lang_file = "language/lang.fr";
}

// Dans le cas o� le script verif_auto_grr.php est utilis� en t�che cron, il faut ici, donner le chemin complet.
if (defined("CHEMIN_COMPLET_GRR")){
  chdir(CHEMIN_COMPLET_GRR);
}
include $lang_file;


// Fichiers de personnalisation de langue
if (@file_exists("language/lang_subst." . $locale))
    include "language/lang_subst." . $locale;


// Fichiers de personnalisation de langue par domaine
if (isset($_GET['area'])) { // Si l'id du domaine est pass� en param�tre, on le r�cup�re
    $subst_id_area = $_GET['area'];
} else if (isset($_GET['room'])) { // sinon, on essaye avec l'id de la ressource
    $subst_id_area = mrbsGetRoomArea($_GET['room']);
}
if (isset($subst_id_area))
  if (@file_exists("language/lang_subst_".$subst_id_area.".".$locale))
    include "language/lang_subst_".$subst_id_area.".".$locale;


// Pour l'affichage de la pendule javascript
$clock_file = "clock_".$locale.".js";

// S�lection du fichier d'aide.
$faqfilelang = "_".$locale;

# Format de la date : 0 pour afficher des dates sous la forme "Jul 10", 1 pour afficher des dates sous la forme "10 Jul"
if ($locale == 'en') {
    $dateformat = 'en';
} else {
    $dateformat = 'fr';
}

switch ($dateformat) {
    case "en":
    $dformat = "%A, %B %d, %Y";
    break;
    case "fr":
    $dformat = "%A %d %B %Y";
    break;
}

function utf8_convert($string)
{
  global $windows_locale, $unicode_encoding, $winlocale_codepage_map;

  if ($unicode_encoding && (get_server_os() == "windows") && $windows_locale)
  {
    if ($winlocale_codepage_map[$windows_locale])
    {
      $string = iconv($winlocale_codepage_map[$windows_locale],"utf-8",$string);
    }
  }
  return $string;
}

function utf8_strftime($format, $time)
{
  $result = strftime($format,$time);
  return utf8_convert($result);
}


# A map is needed to convert from the HTTP language specifier to a
# locale specifier for Windows
$lang_map_windows = array
(
  'cs' => 'csy',
  'cs-cs' => 'csy',
  'cs-cz' => 'csy',
  'cz' => 'csy',
  'cz-cz' => 'csy',
  'da' => 'dan',
  'da-da' => 'dan',
  'de' => 'deu',
  'de-at' => 'dea',
  'de-ch' => 'des',
  'de-de' => 'deu',
  'el' => 'ell',
  'el-el' => 'ell',
  'el-gr' => 'ell',
  'en' => 'eng',
  'en-au' => 'ena',
  'en-ca' => 'enc',
  'en-en' => 'eng',
  'en-ie' => 'eng',
  'en-nz' => 'enz',
  'en-gb' => 'eng',
  'en-us' => 'usa',
  'es' => 'esp',
  'es-es' => 'esp',
  'es-mx' => 'esm',
  'fi' => 'fin',
  'fi-fi' => 'fin',
  'fr' => 'fra',
  'fr-be' => 'frb',
  'fr-ca' => 'frc',
  'fr-ch' => 'frs',
  'fr-fr' => 'fra',
  'it' => 'ita',
  'it-ch' => 'its',
  'it-it' => 'its',
  'ja' => 'jpn',
  'ja-ja' => 'jpn',
  'ja-jp' => 'jpn',
  'ko' => 'kor',
  'ko-ko' => 'kor',
  'ko-kr' => 'kor',
  'nl' => 'nld',
  'nl-be' => 'nlb',
  'nl-nl' => 'nld',
  'no' => 'norwegian',
  'no-no' => 'norwegian',
  'nb' => 'nor',
  'nb-nb' => 'nor',
  'nn' => 'non',
  'nn-nn' => 'non',
  'pl' => 'plk',
  'pl-pl' => 'plk',
  'pt' => 'ptg',
  'pt-br' => 'ptb',
  'pt-pt' => 'ptg',
  'sv' => 'sve',
  'sv-se' => 'sve',
  'sv-sv' => 'sve',
  'zh' => 'chinese',
  'zh-tw' => 'cht',
  'zh-cn' => 'chs',
  'zh-hk' => 'cht',
  'zh-sg' => 'cht',
  'zh-zh' => 'chinese',
);

# This maps a Windows locale to the charset it uses, which are
# all Windows code pages
$winlocale_codepage_map = array
(
  'chs' => 'CP936',
  'cht' => 'CP950',
  'csy' => 'CP1250',
  'dan' => 'CP1252',
  'dea' => 'CP1252',
  'des' => 'CP1252',
  'deu' => 'CP1252',
  'ell' => 'CP1253',
  'ena' => 'CP1252',
  'enc' => 'CP1252',
  'eng' => 'CP1252',
  'enz' => 'CP1252',
  'esm' => 'CP1252',
  'esp' => 'CP1252',
  'fin' => 'CP1252',
  'fra' => 'CP1252',
  'frb' => 'CP1252',
  'frc' => 'CP1252',
  'frs' => 'CP1252',
  'ita' => 'CP1252',
  'its' => 'CP1252',
  'jpn' => 'CP932',
  'kor' => 'CP949',
  'nlb' => 'CP1252',
  'nld' => 'CP1252',
  'norwegian' => 'CP1252',
  'ptb' => 'CP1252',
  'ptg' => 'CP1252',
  'plk' => 'CP1250',
  'sve' => 'CP1252',
  'usa' => 'CP1252'
);

// Cas particulier de la langue fran�aise
// Si la langue fran�aise est choisie et que setlocale(LC_TIME,'fr') renvoie false
// On essaye d'autres possibilit�s
$ok = 'no';
if ($locale == 'fr') {
    $server_os = get_server_os();
    // Si le serveur est sous windows
    if ($server_os == "windows") {
        if ($lang_map_windows[strtolower($locale)]) {
            setlocale(LC_ALL, $lang_map_windows[strtolower($locale)]);
            $windows_locale = $lang_map_windows[strtolower($locale)];
        }
    } else {
        if (!setlocale(LC_TIME,$locale))  {
            if ($unicode_encoding) {
               if (setlocale(LC_TIME,'fr_FR.utf-8') == TRUE) $ok = 'yes';
               else if (setlocale(LC_TIME,'fr_FR.utf8') == TRUE) $ok = 'yes';
               else if (setlocale(LC_TIME,'fr-utf-8') == TRUE) $ok = 'yes';
            } else {
               if (setlocale(LC_TIME,'fr_FR.ISO8859-1') == TRUE) $ok = 'yes';
               else if (setlocale(LC_TIME,'French') == TRUE) $ok = 'yes';
               else if (setlocale(LC_TIME,'france') == TRUE) $ok = 'yes';
               else if (setlocale(LC_TIME,'fra') == TRUE) $ok = 'yes';
               else if (setlocale(LC_TIME,'french') == TRUE) $ok = 'yes';
               else if (setlocale(LC_TIME,'FR') == TRUE) $ok = 'yes';
               else if (setlocale(LC_TIME,'fr_FR') == TRUE) $ok = 'yes';
               else if (setlocale(LC_TIME,'fr_FR@euro') == TRUE) $ok = 'yes';
           }
        }
    }
}
// Les autres langues que le fran�ais
if ($locale != 'fr') {
    $server_os = get_server_os();
    // Si le serveur est sous windows
    if ($server_os == "windows") {
        if ($lang_map_windows[strtolower($locale)]) {
            setlocale(LC_ALL, $lang_map_windows[strtolower($locale)]);
            $windows_locale = $lang_map_windows[strtolower($locale)];
        }
    } else {
        // Si la locale sous la forme de deux lettre (de, en, ...) n'est pas reconue par le serveur,
        // on tente de la mettre sous la forme de_DE, etc...
        // cas perticulier de l'anglais :
        if ($locale == 'en') $locale = "en_US";
        // Autre cas :
        if (strlen($locale) == 2)
        {
           # Convertit locale=xx en xx_XX, ce qui est appropri� � certains syst�me
           $locale = strtolower($locale)."_".strtoupper($locale);
        }
        if ($unicode_encoding) {
            $locale_utf8 .= $locale.".utf-8";
            if (setlocale(LC_TIME,$locale_utf8) == TRUE) $ok = 'yes';
            else
            setlocale(LC_TIME,$locale);
        } else
            setlocale(LC_TIME,$locale);
    }
}


function get_server_os()
{
  if (stristr(PHP_OS,"Darwin"))
  {
    return "unix";
  }
  else if (stristr(PHP_OS, "WIN"))
  {
    return "windows";
  }
  else if (stristr(PHP_OS, "ux") || stristr(PHP_OS, "BSD"))
  {
    return "unix";
  }
  else
  {
    return "unsupported";
  }
}

// Si $unicode_encoding a pour valeur 1, on teste si iconv est activ�
if (($unicode_encoding) && (!function_exists('iconv')))
{
exit("
<p><B>Erreur:</B> Le module iconv, qui permet le support Unicode par PHP, n'est pas install� le serveur.</p>\n
<p>Pour �viter cette erreur, suivez une des deux possibilit�s ci-dessous :</p>
<UL>
<LI><p>Installez et activez le module iconv.<br /></p></LI>
<LI><p>D�sactivez le support Unicode en donnant � la variable \$unicode_encoding la valeur 0 dans le fichier config.inc.php.
</p></LI>
</UL>
");
}

# Get a vocab item, in UTF-8 or a local encoding, depending on the setting of $unicode_encoding
function get_vocab($tag)
{
  global $vocab, $charset_html, $unicode_encoding;
  if (!isset($vocab[$tag])) {
      return "<b><span style=\"color:#FF0000;\"><i>(".$tag.")</i></span></b>";
  } else {
      if ($unicode_encoding)
      {
        return iconv($charset_html,"utf-8",$vocab[$tag]);
      }
      else
      {
        return $vocab[$tag];
      }
  }
}

if (isset($_SESSION['type_month_all'])) $type_month_all = $_SESSION['type_month_all']; else $type_month_all = "month_all";
?>