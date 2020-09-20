<?php

/*
Did i ever told you, that i hate interspire for this file?!
*/

  class LICENSE
  {
      private $_users = 0;
      private $_domain = "";
      private $_expires = "";
      private $_error = false;
      private $_edition = '';
      private $_lists = 0;
      private $_subscribers = 0;
      private $_nfr = '';
      private $_version = '';
      public function GetError()
      {
          return $this->_error;
      }
      public function DecryptKey($jeggcj)
      {
          $this->_users = "1000000000";
          $this->_lists = "1000000000";
          $this->_subscribers = "1000000000";
          $this->_domain = md5(str_replace('www.', '', $_SERVER['HTTP_HOST']));
          $this->_expires = "";
          $this->_edition = md5("ENTERPRISE");
          $this->_version = "5.5";
          $this->_nfr = ""; // WTF? If its needed, insert the value #9f80be86
      }
      public function GetEdition()
      {
          return $this->_edition;
      }
      public function GetUsers()
      {
          return $this->_users;
      }
      public function GetDomain()
      {
          return $this->_domain;
      }
      public function GetExpires()
      {
          return $this->_expires;
      }
      public function GetLists()
      {
          return $this->_lists;
      }
      public function GetSubscribers()
      {
          return $this->_subscribers;
      }
      public function GetVersion()
      {
          return $this->_version;
      }
      public function GetNFR()
      {
          return $this->_nfr;
      }
  }
  function ss9024kwehbehb()
  {
      $jninnoac = constant('SENDSTUDIO_LICENSEKEY');
      $dmblaf = ss02k31nnb($jninnoac);
      if (!$dmblaf) {
          return false;
      }
      $j = $dmblaf->GetUsers();
      $niaoahhgno = IEM::getDatabase();
      $gpkcghpi = 'SELECT COUNT(*) AS count FROM [|PREFIX|]users';
      $hejigcelij = $niaoahhgno->Query($gpkcghpi);
      if (!$hejigcelij) {
          return false;
      }
      $kbfbplnl = $niaoahhgno->Fetch($hejigcelij);
      $kojiknch = $kbfbplnl['count'];
      if ($j <= $kojiknch) {
          return false;
      }
      return true;
  }
  function ssk23twgezm2()
  {
      $gbcemj = constant('SENDSTUDIO_LICENSEKEY');
      $fhpphnhacf = ss02k31nnb($gbcemj);
      if (!$fhpphnhacf) {
          return false;
      }
      $joaegjhio = $fhpphnhacf->GetUsers();
      $nmogkllo = IEM::getDatabase();
      $k = 'SELECT COUNT(*) AS count FROM [|PREFIX|]users';
      $jela = $nmogkllo->Query($k);
      if (!$jela) {
          return false;
      }
      $mbbbn = $nmogkllo->Fetch($jela);
      $okmehne = $mbbbn['count'];
      if ($joaegjhio < $okmehne) {
          return GetLang('UserLimitReached', 'You have reached your maximum number of users and cannot create any more.');
      } else {
          $jejdh = (int)($joaegjhio - $okmehne);
          $ojmcbhnhf = "SELECT COUNT(*) AS count FROM [|PREFIX|]users WHERE admintype != 'a'";
          $jela = $nmogkllo->Query($ojmcbhnhf);
          if (!$jela) {
              return false;
          }
          $mbbbn = $nmogkllo->Fetch($jela);
          $ocmcnl = $mbbbn['count'];
          $einckl = "SELECT COUNT(*) AS count FROM [|PREFIX|]users WHERE admintype = 'a'";
          $jela = $nmogkllo->Query($einckl);
          if (!$jela) {
              return false;
          }
          $mbbbn = $nmogkllo->Fetch($jela);
          $pniklbgl = $mbbbn['count'];
          $h = 'CurrentUserReport';
          $kpejkipjb = 'Current assigned user accounts: %s / admin accounts: %s (Your license key allows you to create %s more account)';
          if ($jejdh != 1) {
              $h .= '_Multiple';
              $kpejkipjb = 'Current assigned user accounts: %s&nbsp;/&nbsp;admin accounts: %s&nbsp;(Your license key allows you to create %s more accounts)';
          }
          $ffodid = sprintf(GetLang($h, $kpejkipjb), $ocmcnl, $pniklbgl, $jejdh);
          if ($jejdh < 1) {
              $ffodid .= '<script>$(function(){$("#createAccountButton").attr("disabled", true)});</script>';
          }
          return $ffodid;
      }
  }
  function ssQmz44Rtt($lchlba = false)
  {
      if (!$lchlba) {
          $lchlba = constant('SENDSTUDIO_LICENSEKEY');
      }
      $gda = ss02k31nnb($lchlba);
      if (!$gda) {
          $nhci = 'Your license key is invalid - possibly an old license key';
          if (substr($lchlba, 0, 3) === 'SS-') {
              $nhci = 'You have an old license key. Please log in to the Interspire Client Area to obtain a new key.';
          }
          return array(true, $nhci);
      }
      if (version_compare('5.5', $gda->GetVersion()) == 1) {
          return array(true, 'You have an old license key.');
      }
      $gal = $gda->GetDomain();
      $abobbjmbbk = $_SERVER['HTTP_HOST'];
      $oodhbgka = (strpos($abobbjmbbk, 'www.') === false) ? 'www.' . $abobbjmbbk : $abobbjmbbk;
      $mnaiknn = str_replace('www.', '', $abobbjmbbk);
      if ($gal != md5($oodhbgka) && $gal != md5($mnaiknn)) {
          return array(true, 'Your license key is not for this domain');
      }
      $f = $gda->GetExpires();
      if ($f != '') {
          if (substr_count($f, '.') === 2) {
              list($hamf, $abndoo, $a) = explode('.', $f);
              $pnf = gmmktime(0, 0, 0, (int)$abndoo, (int)$a, (int)$hamf);
              if ($pnf < gmdate('U')) {
                  return array(true, 'Your license key expired on ' . gmdate('jS F, Y', $pnf));
              }
          } else {
              return array(true, 'Your license key contains an invalid expiration date');
          }
      }
      return array(false, '');
  }
  function ss02k31nnb($gbaac = 'i')
  {
      static $pdolmjaolg = array();
      if ($gbaac == 'i') {
          $gbaac = constant('SENDSTUDIO_LICENSEKEY');
      }
      $gbn = serialize($gbaac);
      if (!array_key_exists($gbn, $pdolmjaolg)) {
          $neaffbi = new License();
          $neaffbi->DecryptKey($gbaac);
          $cidka = $neaffbi->GetError();
          if ($cidka) {
              return false;
          }
          $pdolmjaolg[$gbaac] = $neaffbi;
      }
      return $pdolmjaolg[$gbaac];
  }
  function f0pen()
  {
      $loconpeab = constant('SENDSTUDIO_LICENSEKEY');
      $cianhin = ss02k31nnb($loconpeab);
      if (!$cianhin) {
          return false;
      }
      $obk = md5('STARTER');
      $klmaejoof = md5('PRO');
      $e = md5('ULTIMATE');
      $nhdekf = md5('ENTERPRISE');
      $cd = md5('NORMAL');
      if (defined('SS_SENDGROUP')) {
          return $cianhin;
      }
      if ($cianhin->GetEdition() == $obk) {
          define('SS_RETAIL', serialize(array($obk)));
          define('SS_SENDGROUP', rand(1, 10));
      }
      if ($cianhin->GetEdition() == $klmaejoof) {
          define('SS_SMALLSIZE', serialize(array($obk, $klmaejoof)));
          define('SS_SENDGROUP', rand(20, 50));
      }
      if ($cianhin->GetEdition() == $e) {
          define('SS_MED', serialize(array($obk, $klmaejoof, $e)));
          define('SS_SENDGROUP', rand(100, 500));
      }
      if ($cianhin->GetEdition() == $nhdekf) {
          define('SS_XTRA', serialize(array($obk, $klmaejoof, $e, $nhdekf)));
          define('SS_SENDGROUP', rand(723, 954));
      }
      if ($cianhin->GetEdition() == $cd) {
          $pco = $cianhin->GetExpires();
          if (!empty($pco)) {
              define('SS_TRIAL', rand(512, 1024));
          }
          define('SS_ORIG', serialize(array($obk, $klmaejoof, $e, $nhdekf, $cd)));
          define('SS_SENDGROUP', rand(1027, 5483));
      }
      if ($cianhin->GetNFR()) {
          define('SS_NFR', rand(1027, 5483));
      }
      return $cianhin;
  }
  function installCheck()
  {
      $akgfedbj = func_get_args();
      if (sizeof($akgfedbj) != 2) {
          return false;
      }
      $flffifkgni = array_shift($akgfedbj);
      $gkgk = array_shift($akgfedbj);
      $pjd = ss02k31nnb($flffifkgni);
      $dc = $pjd->GetEdition();
      if ($dc == md5('STARTER') && $gkgk == 'pgsql') {
          return false;
      }
      return true;
  }
  function OK($lkgaf)
  {
      $najfn = ss02k31nnb();
      if (defined($lkgaf)) {
          return false;
      }
      return true;
  }
  function check()
  {
      $jldipcegn = func_get_args();
      $njklfhkmja = f0pen();
      if (!OK('SS_RETAIL')) {
          return false;
      }
      return true;
  }
  function gmt(&$pmobdjnel)
  {
      $e = constant('SENDSTUDIO_LICENSEKEY');
      $anmh = ss02k31nnb($e);
      if (!$anmh) {
          return;
      }
      if ($anmh->GetEdition() == md5('STARTER')) {
          $pmobdjnel->Settings['CRON_ENABLED'] = 0;
          $pmobdjnel->Settings['CRON_SEND'] = 0;
          $pmobdjnel->Settings['CRON_AUTORESPONDER'] = 0;
          $pmobdjnel->Settings['CRON_BOUNCE'] = 0;
      }
  }
  function checkTemplate()
  {
      $bo = func_get_args();
      if (sizeof($bo) != 2) {
          return '';
      }
      $ichigon = strtolower($bo[0]);
      $nkob = f0pen();
      $GLOBALS['Searchbox_List_Info'] = GetLang('Searchbox_List_Info', '(Only visible contact lists/segments you have ticked will be selected)');
      $fg = true;
      if (!OK('SS_RETAIL')) {
          $GLOBALS['ProductEdition'] = 'STARTER';
          $GLOBALS['Searchbox_List_Info'] = GetLang('Searchbox_List_Info_Simple', '(Only visible contact lists you have ticked will be selected)');
      }
      if (!OK('SS_SMALLSIZE')) {
          $GLOBALS['ProductEdition'] = 'PRO';
      }
      if (!OK('SS_MED')) {
          $GLOBALS['ProductEdition'] = 'ULTIMATE';
      }
      if (!OK('SS_XTRA')) {
          $GLOBALS['ProductEdition'] = 'ENTERPRISE';
          $fg = false;
      }
      if (!OK('SS_ORIG')) {
          $fg = false;
          unset($GLOBALS['ProductEdition']);
          $GLOBALS['ShowProd'] = 'none;';
      }
      if (defined('SS_NFR')) {
          if (!isset($GLOBALS['ProductEdition'])) {
              $GLOBALS['ProductEdition'] = '';
          }
          $GLOBALS['ProductEdition'] .= 'Not For Resale';
      }
      if ($ichigon !== 'header') {
          if ($fg) {
              if (!isset($GLOBALS['ProductEdition'])) {
                  $GLOBALS['ProductEdition'] = '';
              }
              $GLOBALS['ProductEdition'] .= GetLang('UpgradeMeLK', '');
          }
      }
      if (!$nkob) {
          return $ichigon;
      }
      if (!OK('SS_RETAIL') || !OK('SS_SMALLSIZE')) {
          $mkkaaeoam = array('user_edit_own', 'user_form');
          if (in_array($ichigon, $mkkaaeoam)) {
              if (!OK('SS_RETAIL')) {
                  return str_replace('user_', 'userok_', $ichigon);
              }
          }
          if (!OK('SS_SMALLSIZE')) {
              return $ichigon;
          }
          $mkkaaeoam = array('lists_manage', 'lists_manage_row', 'send_step1', 'subscriber_manage_step1');
          if (in_array($ichigon, $mkkaaeoam)) {
              return $ichigon . '_1';
          }
          $og = array('settings', 'settings_cron_option');
          if (in_array($ichigon, $og)) {
              return $ichigon . '2';
          }
      }
      return $ichigon;
  }
  function verify()
  {
      $GLOBALS['ListErrorMsg'] = GetLang('TooManyLists', 'You have too many lists and have reached your maximum. Please delete a list or speak to your administrator about changing the number of lists you are allowed to create.');
      $jcpkpgf = func_get_args();
      if (sizeof($jcpkpgf) != 1) {
          return false;
      }
      $kd = f0pen();
      if (!$kd) {
          return false;
      }
      if (!OK('SS_ORIG') && !defined('SS_TRIAL')) {
          return true;
      }
      $oegg = $kd->GetLists();
      if ($oegg == 0) {
          return true;
      }
      if (isset($GLOBALS['DoListChecks'])) {
          return $GLOBALS['DoListChecks'];
      }
      $gngajelk = IEM::getDatabase();
      $pjmpnaab = 'SELECT COUNT(1) AS count FROM [|PREFIX|]lists';
      $helpk = $gngajelk->Query($pjmpnaab);
      $pom = $gngajelk->FetchOne($helpk, 'count');
      if ($pom < $oegg) {
          $GLOBALS['DoListChecks'] = true;
          return true;
      }
      $GLOBALS['ListErrorMsg'] = GetLang('NoMoreLists_LK', 'Your license key does not allow you to create any more mailing lists. Please upgrade.');
      $GLOBALS['DoListChecks'] = false;
      return false;
  }
  function gz0pen()
  {
      $hkhkma = func_get_args();
      if (sizeof($hkhkma) != 4) {
          return false;
      }
      $cfenplbd = strtolower($hkhkma[0]);
      $bmjnpp = strtolower($hkhkma[1]);
      $gipg = f0pen();
      if (!$gipg) {
          if ($cfenplbd == 'system' && $bmjnpp == 'system') {
              return true;
          }
          return false;
      }
      $ahfjb = GetLang('Invalid_LK', 'Your license key does not allow you to do this. Please upgrade!');
      if (!OK('SS_RETAIL')) {
          $jegemnpkh = array('autoresponders', 'subscribers', 'statistics', 'segments');
          if (in_array($cfenplbd, $jegemnpkh)) {
              if ($cfenplbd == 'statistics') {
                  if ($bmjnpp == 'autoresponder') {
                      $GLOBALS['ErrorMessage'] = $ahfjb;
                      return false;
                  }
                  return true;
              }
              if ($cfenplbd == 'subscribers') {
                  $jinimcf = array('banned', 'import', 'export');
                  if (in_array(strtolower($bmjnpp), $jinimcf)) {
                      $GLOBALS['ErrorMessage'] = $ahfjb;
                      return false;
                  }
                  return true;
              }
              return false;
          }
          return true;
      }
      return true;
  }
  function GetDisplayInfo($dg)
  {
      $jgbgfmidig = f0pen();
      if (!$jgbgfmidig) {
          return '';
      }
      $embgac = '';
      $a = $jgbgfmidig->GetExpires();
      if ($a) {
          list($dplbb, $f, $hcebefep) = explode('.', $a);
          $moei = gmdate('U');
          $a = gmmktime(0, 0, 0, $f, $hcebefep, $dplbb);
          $dhpcpfop = floor(($a - $moei) / 86400);
          $akk = 30;
          $k = $akk - $dhpcpfop;
          if ($dhpcpfop <= $akk) {
              if (!defined('LNG_UrlPF_Heading')) {
                  define('LNG_UrlPF_Heading', '%s Day Free Trial');
              }
              $GLOBALS['PanelDesc'] = sprintf(GetLang('UrlPF_Heading', '%s Day Free Trial'), $akk);
              $GLOBALS['Image'] = 'upgrade_bg.gif';
              $ggofghiod = str_replace('id="popularhelparticles"', 'id="upgradenotice"', $dg->ParseTemplate('index_popularhelparticles_panel', true));
              if (!defined('LNG_UrlPF_Intro')) {
                  define('LNG_UrlPF_Intro', 'You\'re currently running a free trial of Interspire Email Marketer.%sYou\'re on day %s of your %s day free trial.');
              }
              if (!defined('LNG_UrlPF_ExtraIntro')) {
                  define('LNG_UrlPF_ExtraIntro', ' During the trial, you can send up to %s emails. ');
              }
              if (!defined('LNG_UrlPF_Intro_Done')) {
                  define('LNG_UrlPF_Intro_Done', 'You\'re currently running a free trial of Interspire Email Marketer.%sYour license key expired %s days ago.');
              }
              if (!defined('LNG_UrlP')) {
                  define('LNG_UrlP', '');
              }
              $bjcbmm = '<br/><p style="text-align: left;">' . GetLang('UrlP', '') . '</p>';
              $npgl = GetLang('UrlPF_Intro', 'You are currently running a free trial of Interspire Email Marketer.%sYou\'re on day %s of your %s day free trial.') . $bjcbmm;
              $dankljhn = GetLang('UrlPF_Intro_Done', 'You are currently running a free trial of Interspire Email Marketer.%sYour license key expired %s days ago.') . $bjcbmm;
              $gj = '';
              $hccpieaoa = $jgbgfmidig->GetSubscribers();
              if ($hccpieaoa > 0) {
                  $gj = sprintf(GetLang('UrlPF_ExtraIntro', ' During the trial, you can send up to %s emails. '), $hccpieaoa);
              }
              if ($dhpcpfop > 0) {
                  $ggofghiod = str_replace('</ul>', '<p>' . sprintf($npgl, $gj, $k, $akk) . '</p></ul>', $ggofghiod);
              } else {
                  $ggofghiod = str_replace('</ul>', '<p>' . sprintf($dankljhn, $gj, ($dhpcpfop * -1)) . '</p></ul>', $ggofghiod);
              }
              $GLOBALS['SubPanel'] = $ggofghiod;
              $mnkllkijld = $dg->ParseTemplate('indexpanel', true);
              $mnkllkijld = str_replace('style="background: url(images/upgrade_bg.gif) no-repeat;padding-left: 20px;"', '', $mnkllkijld);
              $mnkllkijld = str_replace('class="DashboardPanel"', 'class="DashboardPanel UpgradeNotice"', $mnkllkijld);
              $embgac .= $mnkllkijld;
          }
      }
      if (!OK('SS_ORIG')) {
          return $embgac;
      }
      $klllpf = $jgbgfmidig->GetSubscribers();
      $gecmfkmj = IEM::getDatabase();
      $gdhbkcbanl = 'SELECT SUM(subscribecount) as total FROM [|PREFIX|]lists';
      $nfoedep = $gecmfkmj->FetchOne($gdhbkcbanl);
      $GLOBALS['PanelDesc'] = GetLang('ImportantInformation', 'Important Information');
      $GLOBALS['Image'] = 'info.gif';
      $ggofghiod = str_replace('popularhelparticles', 'importantinfo', $dg->ParseTemplate('index_popularhelparticles_panel', true));
      $ibf = false;
      if ($nfoedep > $klllpf) {
          $GLOBALS['Image'] = 'error.gif';
          $ggofghiod = str_replace('</ul>', sprintf(GetLang('Limit_Over', 'You are over the maximum number of contacts you are allowed to have. You have <i>%s</i> in total and your limit is <i>%s</i>. You will only be able to send to a maximum of %s at a time.'), $dg->FormatNumber($nfoedep), $dg->FormatNumber($klllpf), $dg->FormatNumber($klllpf)) . '</ul>', $ggofghiod);
          $ibf = true;
      } elseif ($nfoedep == $klllpf) {
          $GLOBALS['Image'] = 'warning.gif';
          $ggofghiod = str_replace('</ul>', sprintf(GetLang('Limit_Reached', 'You have reached the maximum number of contacts you are allowed to have. You have <i>%s</i> contacts and your limit is <i>%s</i> in total.'), $dg->FormatNumber($nfoedep), $dg->FormatNumber($klllpf)) . '</ul>', $ggofghiod);
          $ibf = true;
      } elseif ($nfoedep > (0.7 * $klllpf)) {
          $ggofghiod = str_replace('</ul>', sprintf(GetLang('Limit_Close', 'You are reaching the total number of contacts for which you are licensed. You have <i>%s</i> contacts and your limit is <i>%s</i> in total.'), $dg->FormatNumber($nfoedep), $dg->FormatNumber($klllpf)) . '</ul>', $ggofghiod);
          $ibf = true;
      }
      if ($ibf) {
          $GLOBALS['SubPanel'] = $ggofghiod;
          $embgac .= $dg->ParseTemplate('indexpanel', true);
      }
      if ($jgbgfmidig->GetEdition() == md5('STARTER')) {
          $GLOBALS['PanelDesc'] = GetLang('ImportantInformation_Start', 'Upgrade and Send More Emails Today!');
          $GLOBALS['Image'] = 'upgrade_bg.gif';
          $ggofghiod = str_replace('id="popularhelparticles"', 'id="upgradenotice"', $dg->ParseTemplate('index_popularhelparticles_panel', true));
          $ggofghiod = str_replace('</ul>', GetLang('UpgradeNoticeInfo', '
			<p>
				You are currently running the Starter edition of Interspire Email Marketer.
				Upgrade today to send more emails and access more features including:
			</p>
			<ul>
				<li>Send thousands or millions of emails</li>
				<li>Create and send automatic emails</li>
				<li>Segment and filter your contact lists</li>
				<li>Track campaigns with Google Analytics support</li>
				<li>Export your contact lists</li>
				<li>Schedule emails to be sent at a later date</li>
				<li>Import contacts from your existing system</li>
			</ul>
		') . '</ul>', $ggofghiod);
          $GLOBALS['SubPanel'] = $ggofghiod;
          $mnkllkijld = $dg->ParseTemplate('indexpanel', true);
          $mnkllkijld = str_replace('style="background: url(images/upgrade_bg.gif) no-repeat;padding-left: 20px;"', '', $mnkllkijld);
          $mnkllkijld = str_replace('class="DashboardPanel"', 'class="DashboardPanel UpgradeNotice"', $mnkllkijld);
          $embgac .= $mnkllkijld;
      }
      return $embgac;
  }
  function checksize($egopklgcgm, $o, $icpfkfmado)
  {
      if ($o === 'true') {
          return;
      }
      if (!$icpfkfmado) {
          return;
      }
      $nhgjjegoab = f0pen();
      if (!$nhgjjegoab) {
          return;
      }
      IEM::sessionRemove('SendSize_Many_Extra');
      IEM::sessionRemove('ExtraMessage');
      IEM::sessionRemove('MyError');
      $h = 0;
      if (OK('SS_ORIG') || defined('SS_TRIAL') || defined('SS_NFR')) {
          $h = $nhgjjegoab->GetSubscribers();
          if ($egopklgcgm > $h) {
              IEM::sessionSet('SendSize_Many_Extra', $h);
              $db = false;
          } else {
              $db = true;
          }
      } else {
          $db = true;
      }
      if (defined('SS_NFR')) {
          $iaalaachem = 0;
          $lhjegbcgpe = IEM_STORAGE_PATH . '/.sess_9832499kkdfg034sdf';
          if (is_readable($lhjegbcgpe)) {
              $gkn = file_get_contents($lhjegbcgpe);
              $iaalaachem = base64_decode($gkn);
          }
          if ($iaalaachem > 1000) {
              $d = 'This is an NFR copy of Interspire Email Marketer. You are only allowed to send up to 1,000 emails using this copy.\n\nFor further details, please see your NFR agreement.';
              IEM::sessionSet('ExtraMessage', '<script>$(document).ready(function() {alert(\'' . $d . '\'); document.location.href=\'index.php\'});');
              $ijdcajnb = new SendStudio_Functions();
              $lnimibkh = $ijdcajnb->FormatNumber(0);
              $bemkhkc = $ijdcajnb->FormatNumber($egopklgcgm);
              $ikkfojkl = sprintf(GetLang($fcfcdjfoc, $e), $ijdcajnb->FormatNumber($egopklgcgm), '');
              IEM::sessionSet('MyError', $ijdcajnb->PrintWarning('SendSize_Many_Max', $lnimibkh, $bemkhkc, $lnimibkh));
              IEM::sessionSet('SendInfoDetails', array('Msg' => $ikkfojkl, 'Count' => $paakpiap));
              return;
          }
          $iaalaachem += $egopklgcgm;
          @file_put_contents($lhjegbcgpe, base64_encode($iaalaachem));
      }
      IEM::sessionSet('SendRetry', $db);
      if (!class_exists('Sendstudio_Functions')) {
          require_once dirname(__FILE__) . '/sendstudio_functions.php';
      }
      $ijdcajnb = new SendStudio_Functions();
      $fcfcdjfoc = 'SendSize_Many';
      $e = 'This email campaign will be sent to approximately %s contacts.';
      $kheoco = '';
      $paakpiap = min($h, $egopklgcgm);
      if (!OK('SS_ORIG') && (!defined('SS_TRIAL') || !defined('SS_NFR'))) {
          $paakpiap = $egopklgcgm;
      }
      if (!$db) {
          $lnimibkh = $ijdcajnb->FormatNumber($h);
          $bemkhkc = $ijdcajnb->FormatNumber($egopklgcgm);
          IEM::sessionSet('MyError', $ijdcajnb->PrintWarning('SendSize_Many_Max', $lnimibkh, $bemkhkc, $lnimibkh));
          if (defined('SS_NFR')) {
              $d = sprintf(GetLang('SendSize_Many_Max_Alert', '--- Important: Please Read ---\n\nThis is an NFR copy of the application. This limit your sending to a maximum of %s emails. You are trying to send %s emails, so only the first %s emails will be sent.'), $lnimibkh, $bemkhkc, $lnimibkh);
          } else {
              $d = sprintf(GetLang('SendSize_Many_Max_Alert', '--- Important: Please Read ---\n\nYour license allows you to send a maximum of %s emails at once. You are trying to send %s emails, so only the first %s emails will be sent.\n\nTo send more emails, please upgrade. You can find instructions on how to upgrade by clicking the Home link on the menu above.'), $lnimibkh, $bemkhkc, $lnimibkh);
          }
          IEM::sessionSet('ExtraMessage', '<script>$(document).ready(function() {alert(\'' . $d . '\');});');
      }
      $ikkfojkl = sprintf(GetLang($fcfcdjfoc, $e), $ijdcajnb->FormatNumber($egopklgcgm), $kheoco);
      IEM::sessionSet('SendInfoDetails', array('Msg' => $ikkfojkl, 'Count' => $paakpiap));
  }
  function setmax($lhjolfk, &$n)
  {
      if ($lhjolfk === 'true' || $lhjolfk === '-1') {
          return;
      }
      $nppp = f0pen();
      if (!$nppp) {
          $n = '';
          return;
      }
      if (!OK('SS_ORIG') && !defined('SS_TRIAL')) {
          return;
      }
      $badmc = $nppp->GetSubscribers();
      $n .= ' ORDER BY l.subscribedate ASC LIMIT ' . $badmc;
  }
  function del_user_dir($maplmee = 0)
  {
      create_user_dir($maplmee);
      if ($maplmee > 0) {
          remove_directory(TEMP_DIRECTORY . '/user/' . $maplmee);
      }
  }
  function create_user_dir($mgfmcim = 0)
  {
      if ($mgfmcim > 0) {
          CreateDirectory(TEMP_DIRECTORY . '/user/' . $mgfmcim);
      }
  }
  function check_user_dir()
  {
      return;
  }