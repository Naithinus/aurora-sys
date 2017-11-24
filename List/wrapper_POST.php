<?php

    set_time_limit(0);
	ini_set("memory_limit","9000M");
	ini_set("upload_max_filesize","300000M");
	ini_set("post_max_size","300000M");
	ini_set("max_execution_time","0");
	ini_set("max_input_time","0"); 
	 
	 include_once('../Includes/sessionVerification.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 

	 
function upload()
{
      $data = $_FILES['data']['name'];
	  $extension = strtolower(pathinfo($data, PATHINFO_EXTENSION));
	  $validExtensions = array('txt','csv');
	  if(in_array($extension,$validExtensions))
	    $res = move_uploaded_file($_FILES['data']['tmp_name'],'original/'.$data);
	if($res)
		echo 'uploaded';
	else
		echo 'not uploaded';
}
   
$fileAolUsa 	= 	fopen('tmp/aolUSA.txt','w+');
$fileAolUK 		= 	fopen('tmp/aolUK.txt','w+');
$fileGmailUsa 		= 	fopen('tmp/gmailUSA.txt','w+');
$fileHotmailUSA = 	fopen('tmp/hotmailUSA.txt','w+');
$fileHotmailUK  = 	fopen('tmp/hotmailUK.txt','w+');
$fileHotmailAU  = 	fopen('tmp/hotmailAU.txt','w+');
$fileComcast	= 	fopen('tmp/comcastUSA.txt','w+');
$fileCox		= 	fopen('tmp/coxUSA.txt','w+');
$fileiCloud		=	fopen('tmp/icloudUSA.txt','w+');
$fileATT		=	fopen('tmp/attUSA.txt','w+');
$fileYahooUSA	=	fopen('tmp/yahooUSA.txt','w+');
$fileGmxUSA		=	fopen('tmp/gmxUSA.txt','w+');
$filebellsouth  =   fopen('tmp/bellsouthUSA.txt','w+');
$filesbcglobal  =   fopen('tmp/sbcglobalUSA.txt','w+');
$filenetzero    =   fopen('tmp/netzeroUSA.txt','w+');
$fileverizon    =   fopen('tmp/verizonUSA.txt','w+');
$filejuno       =   fopen('tmp/junoUSA.txt','w+');
$filevirginUK  =   fopen('tmp/virginUK.txt','w+');
$filebtinternet =   fopen('tmp/btinternetUSA.txt','w+');
$fileroadrunner =   fopen('tmp/roadrunnerUSA.txt','w+');
$filetalktalk =   fopen('tmp/talktalkUK.txt','w+');
$filesfr =   fopen('tmp/sfrFR.txt','w+');
$data = $_FILES['data']['name'];
upload();



$isOptIn = (isset($_POST['chkIsOptIN'])) ? 1 : 0;
$file = fopen('original/'.$data,'r+');


if($isOptIn == 0)
{
    
	while($line = fgets($file))
	{
	  $line  = strtolower($line);
	  $split = explode('@',trim($line));
	  switch(strtolower($split[1]))
	  {
	     
		//Talk Talk :
		case'homecall.co.uk':
		case'talktalk.net':
		case'lineone.net':
		case'toucansurf.com':
		case'dsl.pipex.com':
		case'tinyworld.co.uk':
		case'tiscali.co.uk':
		case'onetel.com':
		case'ukgateway.net':
		case'tinyonline.co.uk':
		case'dial.pipex.com':
		case'worldonline.co.uk':
		case'screaming.net':	
		{
		    fputs($filetalktalk,$line);
		}
		break;
		
		
		
		
		
		
		
		
		//AOL USA : 
		
		case 'aol.com':
		case 'aol.net':
		case 'aim.com':
		case 'aim.net':
		case 'cs.com':
		case 'cs.net':
		case 'netscape.com':
		case 'netscape.net':
		case 'compuserve.com':
		case 'wmconnect.com':
		case 'luckymail.com':
		{
		    fputs($fileAolUsa,$line);
		}
		break;
		
		
		//AOL UK : 
		case 'aol.co.uk':
		case 'aol.com':
		case 'aol.net':
		case 'aim.com':
		case 'aim.net':
		case 'cs.com':
		case 'cs.net':
		case 'netscape.com':
		case 'netscape.net':
		case 'compuserve.com':
		case 'wmconnect.com':
		case 'luckymail.com':
		{
		    fputs($fileAolUK,$line);
		}
		break;
		
		
		
		//Virgin Media :
		case 'virgin.net':
		case 'virginmedia.com':
		case 'virginmedia.co.uk':
		case 'virginbroadband.com.au':
		case 'sfr.fr':
		case 'club-internet.fr':
		case 'neuf.fr':
		
		{
		    fputs($filevirginUK,$line);
		}
		break;
		
		
		
		//Google 
		 case 'gmail.com':
		 case 'googlemail.com':
		 {
		    fputs($fileGmailUsa,$line);
		 }
		 break;
		 
		 case 'att.net':
		 {
		    fputs($fileATT,$line);
		 }
		 break;
		 
		 case 'rr.com':
		 case 'roadrunner.com':
		 {
		    fputs($fileroadrunner,$line);
		 }
		 break;
		 
		 case 'bellsouth.net':
		 {
		    fputs($filebellsouth,$line);
		 }
		 break;
		 
		  case 'sbcglobal.net':
		 {
		    fputs($filesbcglobal,$line);
		 }
		 
		 
		  break;
		 
		  case 'juno.net':
		  case 'juno.com':
		 {
		    fputs($filejuno,$line);
		 }
		 break;
		 
		 
			
		 
		 
		 
		  case 'netzero.com':
		  case 'netzero.net':
		 {
		    fputs($filenetzero,$line);
		 }
		 
		 break;
		 
		  case 'btopenworld.com':
		  case 'btinternet.net':
		  case 'btinternet.com':
		 {
		    fputs($filebtinternet,$line);
		 }
		 
		  break;
		  
		  
		  
		 case 'verizon.net':
		  case 'verizon.com':
		 {
		    fputs($fileverizon,$line);
		 }
		 
		  break;
		 
		 case 'comcast.net':
		 {
		    fputs($fileComcast,$line);
		 }
		 break;
		 
		 
		 case 'cox.net':
		 {
		    fputs($fileCox,$line);
		 }
		 break;
		 
		 case 'icloud.com':
		 case 'mac.com':
		 {
		    fputs($fileiCloud,$line);
		 }
		 break;
		 
		 
		  
		//Hotmail USA :  
		case 'hotmail.com':
		case 'msn.com':
		case 'live.com':
		case 'outlook.com':
		{
		    fputs($fileHotmailUSA,$line);
		}
		break;
		

		
		//Hotmail UK :
		case 'hotmail.co.uk':
		case 'msn.co.uk':
		case 'live.co.uk':
		case 'outlook.co.uk':
		case 'hotmail.com':
		case 'msn.com':
		case 'live.com':
		case 'outlook.com':
		{
		    fputs($fileHotmailUK,$line);
		}
		break;
		
		
		
		
		//Hotmail AU :
		case 'hotmail.com.au':
		case 'live.com.au':
		case 'msn.com.au':
		case 'outlook.com.au':
		case 'hotmail.com':
		case 'outlook.com':
		case 'live.com':
		case 'msn.com':
		case 'hotmail.co.uk':
		case 'msn.co.uk':
		case 'live.co.uk':
		case 'outlook.co.uk':
		{
		    fputs($fileHotmailAU,$line);
		}
		break;  
		
		  
		  
		  
		  
		  
		  
		  
			//GMX : 
			case 'mail.com':
			case 'mail.dc.state.fl.us':
			case 'mail-me.com':
			case 'mail.broward.edu':
			case 'mailout10.bedhash.com':
			case 'mail2go.com':
			case 'mailanthus.com':
			case 'mail.comx':
			case 'mail.ru':
			case 'mail-lb2-int.dca2.superb.net':
			case 'mail.ccsf.edu':
			case 'mail.usa.com':
			case 'mail.twu.edu':
			case 'mail.usmagazine.com':
			case 'mailbox.swipnet.se':
			case 'mail.lipscomb.edu':
			case 'mailsamolatina.com':
			case 'mail.fcboe.org':
			case 'mailcity.com':
			case 'mail.comp':
			case 'mail.mil':
			case 'mail137.subscribermail.com':
			case 'mail23.dataentry-jobs.net':
			case 'mail2world.com':
			case 'mail2greece.com':
			case 'mail2think.com':
			case 'mail.wlu.edu':
			case 'mail.uc.edu':
			case 'mail.martinmethodist.edu':
			case 'mail.gvsu.edu':
			case 'mail.sio.midco.net':
			case 'mail2angela.com':
			case 'mailshipsolutions.com':
			case 'mail.cngold.org':
			case 'mail.lrm2.k12.wy.us':
			case 'mail2joseph.com':
			case 'mail.clxcpu.com':
			case 'mail.gatech.edu':
			case 'mail.kmutnb.ac.th':
			case 'mail.maehdros.be':
			case 'mail.networksolutionsemail.com':
			case 'mail.pioneeris.net':
			case 'mailpod.hostingplatform.com':
			case 'mailrelay4.gazprom.ru':
			case 'mail.plymouth.edu':
			case 'mail.mg-advertising.net':
			case 'mail.mn':
			case 'mail2maxwell.com':
			case 'mailer.com':
			case 'mail.missouri.edu':
			case 'mail2cool.com':
			case 'mail.uajy.ac.id':
			case 'mailcan.com':
			case 'mail333.com':
			case 'mailbag.com':
			case 'mail15.com':
			case 'mailtorch.com':
			case 'mailas.com':
			case 'mail.mvnu.edu':
			case 'mail.wccaheadstart.org':
			case 'mail.usi.edu':
			case 'mail.med.upenn.edu':
			case 'mailrelay2.gazprom.ru':
			case 'mail.tascom.ru':
			case 'mail.internetseer.com':
			case 'mail.org':
			case 'mail.hamiltontn.gov':
			case 'mail.okaloosa.k12.fl.us':
			case 'mailbox.sc.edu':
			case 'mail.clayton.edu':
			case 'mailcc.com':
			case 'mail.smu.edu':
			case 'mail.barry.edu':
			case 'mailhost.polarhome.com':
			case 'mailcui.com':
			case 'mail.roanoke.edu':
			case 'mail.bbahranice.cz':
			case 'mail-in5-pp.ewetel.de':
			case 'mail.com.com':
			case 'mailblc.org':
			case 'mail2art.com':
			case 'mail.kz':
			case 'mail.slh.wisc.edu':
			case 'mail.tpchel.ru':
			case 'mail.1stcml.com':
			case 'mail.kingsizedirect.com':
			case 'mail1.stofanet.dk':
			case 'mail.davenport.k12.ia.us':
			case 'mail.hotmail.com':
			case 'mail2michelle.com':
			case 'mail.auis.net':
			case 'mailbolt.con':
			case 'mail.bg':
			case 'mailbolt.com':
			case 'mail.bubblers.k12.pa.us':
			case 'mail.ftc.org':
			case 'mail4y.com':
			case 'mail.cox.smu.edu':
			case 'mail.mhanet.com':
			case 'mail.bw.edu':
			case 'mail.net':
			case 'mail.manti.com':
			case 'mail.comn':
			case 'mailinator.com':
			case 'mail.amc.edu':
			case 'mail.chpaman.edu':
			case 'mail.usf.edu':
			case 'mail.atlantisadventures.com':
			case 'maildiablo.com':
			case 'mail2web.com':
			case 'mail2usa.com':
			case 'mail2cowgirl.com':
			case 'mailsnare.net':
			case 'mail2cute.com':
			case 'mail2queen.com':
			case 'mailworksllc.com':
			case 'mail.cm':
			case 'mail2doris.com':
			case 'mail2christy.com':
			case 'mail.etsu.edu':
			case 'mail.montclair.edu':
			case 'mail.bradley.edu':
			case 'mailexcite.com':
			case 'mailbox.net':
			case 'mail.comspeculation':
			case 'mail.comff':
			case 'mailpac.net':
			case 'mail.orbitel.bg':
			case 'mail.easynet.co.uk':
			case 'mail.telepac.pt':
			case 'mailattache.com':
			case 'mailonline.com':
			case 'mail.mylife.com':
			case 'mail.mst.edu':
			case 'mail.md':
			case 'mailmt.com':
			case 'mail.saturnfans.com':
			case 'mail.coloradomtn.edu':
			case 'mail.fr':
			case 'mail.stmarytx.edu':
			case 'mail.fresnostate.edu':
			case 'mailstation.com':
			case 'mail.cim':
			case 'mail.dccc.edu':
			case 'mail.om':
			case 'mail.wvu.edu':
			case 'mail.dumas-k12.net':
			case 'mail.chattanooga.gov':
			case 'mailer.fsu.edu':
			case 'mailtothis.com':
			case 'mail.k12.tn.us':
			case 'mail.cccd.edu':
			case 'mailboxprintandmail.com':
			case 'mail.uri.edu':
			case 'mail.oratoryschools.org':
			case 'mail.cocke.k12.tn.us':
			case 'mail.guam.net':
			case 'mail.xj-n-tax.gov.cn':
			case 'mail.ubc.ca':
			case 'mail.ci.lubbock.tx.us':
			case 'mail.roosevelt.edu':
			case 'mail.vresp.com':
			case 'mailtag.com':
			case 'mailpuppy.com':
			case 'mail.riverview.net':
			case 'mail.umsl.edu':
			case 'mail2dancer.com':
			case 'mail.house.gov':
			case 'mail.afats.khc.edu.tw':
			case 'mail.ranbowintl.com':
			case 'mail.co':
			case 'mail.va':
			case 'mailde.de':
			case 'mailjmc.com':
			case 'mail.siom.ac.cn':
			case 'mail.widener.edu':
			case 'mail.com18':
			case 'mailchimp.com':
			case 'mail.lced.net':
			case 'mail.goucher.edu':
			case 'maila.com':
			case 'mail2.dpa.de':
			case 'mailstop.com':
			case 'mailstop.co':
			case 'mailzeta.com':
			case 'mailandbusiness.com':
			case 'mailsitedirect.us':
			case 'mail.mizzou.edu':
			case 'mailtous.com':
			case 'mail.mccneb.edu':
			case 'mailstjohn.com':
			case 'mail.depaul.edu':
			case 'mailboxgenie.com':
			case 'mail.umkc.edu':
			case 'mail-nbf.kaydon.com':
			case 'mail.funtime-parts.de':
			case 'mail.glassdoctor.com':
			case 'mail.gtc.edu':
			case 'mail.come':
			case 'mailmalaga.com':
			case 'mail.raonoke.edu':
			case 'mail.csdnet.com.ar':
			case 'mailbox-3.com':
			case 'mailimate.com':
			case 'mail.fshu.edu':
			case 'mail.usciences.edu':
			case 'mail.sy':
			case 'mail.mpt.org':
			case 'mailhaven.com':
			case 'mail.shcnc.ac.cn':
			case 'mailtoko.com':
			case 'mail.shu.edu.cn':
			case 'mail.buffalostate.edu':
			case 'mail.mailinfinity.com':
			case 'mailinfinity.com':
			case 'mailturk.net':
			case 'mail.cfcc.edu':
			case 'mails.thu.edu.cn':
			case 'mail.mauricionassau.com.br':
			case 'mail.epc.k12.ar.us':
			case 'mail.hellosign.com':
			case 'mailpro1.com':
			case 'mail.kana.k12.wv.us':
			case 'mail54.fssprus.ru':
			case 'mailtmi.com':
			case 'mail.pf':
			case 'mailmix.pl':
			case 'maildrop.cc':
			case 'mail2.thejakartapost.com':
			case 'mailance.com':
			case 'mailcentrals.co.uk':
			case 'mail.xom':
			case 'mail4me.com':
			case 'mail2games.com':
			case 'mail.utoronto.ca':
			case 'mail.yzu.edu.tw':
			case 'mailsystem.au':
			case 'mail.unkc.edu':
			case 'mail-a.tvnetwork.hu':
			case 'mailfa.com':
			case 'mailismagic.com':
			case 'mail.nwmissouri.edu':
			case 'mail.c':
			case 'mail.con':
			case 'mail.lcc.edu':
			case 'mail.sunysuffolk.edu':
			case 'mail-efax.co.uk':
			case 'mail.cin':
			case 'mailbox.hu':
			case 'mail.blueonyx.it':
			case 'mailforspam.com':
			case 'maill.cim':
			case 'mailserver.i-next.psi.br':
			case 'maill.com':
			case 'mail.tt':
			case 'mail.fruitvaleisd.com':
			case 'mail.hz.zj.cn':
			case 'mail.winchart.com.tw':
			case 'mail.comam':
			case 'mail.redzone.com.au':
			case 'mail.la-archdiocese.net':
			case 'mail.extravaganza-sweepstakes.com':
			case 'mail.xmission.com':
			case 'mailfw.whoisproxy.com':
			case 'mail.couk':
			case 'mail.weber.edu':
			case 'mail.missouri.mail':
			case 'mail.lcs.net':
			case 'mail.ap-hm.fr':
			case 'mail.sogo.com.tw':
			case 'mail.c8m':
			case 'mail.cam':
			case 'mail.law.cuny.edu':
			case 'mail.hzic.edu.cn':
			case 'mail174.bizfree.kr':
			case 'mailbag.net':
			case 'mail.gmail.com':
			case 'mail2go.net':
			case 'mail.westco.net':
			case 'mail.endicott.edu':
			case 'mail.tmccentral.org':
			case 'mailoo.org':
			case 'mail.cmich.edu':
			case 'maildsi.com':
			case 'mail.austria.com':
			case 'mailplex.com':
			case 'mailtrace.cjb.net':
			case 'mail.sdsu.edu':
			case 'mail.sfsu.edu':
			case 'mailspot.org':
			case 'mail.cstudies.ubc.ca':
			case 'mails-gw.fnbs.net.my':
			case 'mail.ustc.edu.cn':
			case 'mail.wlc.edu':
			case 'mail.ms.maquoketa.k12.ia.us':
			case 'mail.scut.edu.cn':
			case 'mail.gardensnyc.net':
			case 'mailfrom.ru':
			case 'mail12.world4you.com':
			case 'mail.mira.dk':
			case 'mail.iccfa.com':
			case 'mail.sacredheart.edu':
			case 'mailbox99.net':
			case 'mail.darton.edu':
			case 'mail.ts-zipper.com.tw':
			case 'mail-exit-bc.narrowsuite.com':
			case 'mail-bullet-hubs.sicens.net':
			case 'mailtcs.com':
			case 'mail.co.uk':
			case 'mailrsi.com':
			case 'mailtpa.com':
			case 'mailnull.com':
			case 'mail.mcgill.ca':
			case 'mail.chapman.edu':
			case 'mailcatch.com':
			case 'mail.nih.gov':
			case 'mail.nplindia.ernet.in':
			case 'mail.oac.uncor.edu':
			case 'mail.nccu.edu':
			case 'mail2star.com':
			case 'mailserver.surfpacific.co':
			case 'mail.enet.com.cn':
			case 'mail.cometc':
			case 'mail.warp.co.nz':
			case 'mail.china.cn':
			case 'mail.rmu.edu':
			case 'mailaaa.com':
			case 'mail2king.com':
			case 'mail.worcester.k12.md.us':
			case 'mailprint.com':
			case 'mailpring.com':
			case 'mail.exis.it':
			case 'mail.aim-net.mx':
			case 'mail.nist.ro':
			case 'mail.rb.ru':
			case 'mail2ronald.com':
			case 'mail3.newulmtel.net':
			case 'mailboxz.net':
			case 'mail.primelsolutions.com':
			case 'mail9.ufmg.br':
			case 'mail.coming':
			case 'mail.fhsu.edu':
			case 'mail.jkes.tp.edu.tw':
			case 'mail.boonty.com':
			case 'mail.casapellas.com.ni':
			case 'mail.ro':
			case 'mail.umw.edu':
			case 'mailumsl.edu':
			case 'mail.win.org':
			case 'mail.regent.edu':
			case 'mailfrhub.com':
			case 'mail.comcast.net':
			case 'mail.comhot':
			case 'mail.comi':
			case 'mail.comg':
			case 'mail.comomfice':
			case 'mail.comro':
			case 'mail.comtasea':
			case 'mail.com.np':
			case 'mail.hocking.edu':
			case 'mail.plattsburgh.edu':
			case 'mail.techno-link.com':
			case 'mail.flyon.net':
			case 'mail.wvncc.edu':
			case 'mail2roy.com':
			case 'mailmeonline.net':
			case 'mailfern.com':
			case 'mail.techno-linc.com':
			case 'mail.ab.mec.edu':
			case 'usa.com':
			case 'usa.co':
			case 'usa.net':
			case 'usadig.com':
			case 'usa-spirit.com':
			case 'usabiz.biz':
			case 'usahealthvip.com':
			case 'usalaptoprepair.com':
			case 'usalendinginc.com':
			case 'usa2net.net':
			case 'usa-companies.net':
			case 'usagis-house.net':
			case 'usadma.com':
			case 'usamortgageinc.com':
			case 'usawide.net':
			case 'usamedia.tv':
			case 'usadutchinc.com':
			case 'usace.army.mil':
			case 'usa-labor.com':
			case 'usashs.com':
			case 'usadv.com':
			case 'usainnmotel.com':
			case 'usabg.net':
			case 'usasia-ins.com':
			case 'usabowman.com':
			case 'usana.com':
			case 'usanfsc.com':
			case 'usa.comif':
			case 'usadvantage.com':
			case 'usa-empire.com':
			case 'usastaffingnetwork.com':
			case 'usairlinepilots.org':
			case 'usableinterface.com':
			case 'usasend.com':
			case 'usa-networking.com':
			case 'usaprotection.com':
			case 'usa.comr':
			case 'usadrivers.com':
			case 'usahealth.edu':
			case 'usarmyvelo-1.com':
			case 'usabras.com':
			case 'usamedicus.com':
			case 'usalp.org':
			case 'usap.com':
			case 'usafricasynergy.org':
			case 'usadjustingservices.net':
			case 'usairways.com':
			case 'usa.varioline.com':
			case 'usaabs.com':
			case 'usainternationaldata.com':
			case 'usabioproducts.com':
			case 'usa2net.nert':
			case 'usalimoandsedan.com':
			case 'usahandcrafted.com':
			case 'usabel.com':
			case 'usagainstgreed.org':
			case 'usa.g4s.com':
			case 'usafa.edu':
			case 'usa.xerox.com':
			case 'usascheduler.com':
			case 'usaircraftsales.com':
			case 'usabilitycounts.com':
			case 'usatechsearch.com':
			case 'usa-travel-agency.com':
			case 'usafill.com':
			case 'usa-shade.com':
			case 'usa.cipom':
			case 'usaforever.net':
			case 'usadmail.com':
			case 'usatco.co.uk':
			case 'usa-security.net':
			case 'usa-11.com':
			case 'usace.amry.mil':
			case 'usaninc.com':
			case 'usaalarmsystems.com':
			case 'usa.apachecorp.com':
			case 'usa.ibs.org':
			case 'usaprivatesecurity.com':
			case 'usalendingandrealty.com':
			case 'usataxiandlimo.com':
			case 'usamusiic.org':
			case 'usafloortec.com':
			case 'usaddress.us':
			case 'usa.cm':
			case 'usarecyclingcenters.com':
			case 'usa-tsg.com':
			case 'usaswim.com':
			case 'usacracing.com':
			case 'usaaap.com':
			case 'usautomationcorp.com':
			case 'usaviator.net':
			case 'usaa.com':
			case 'usapromotionalcards.com':
			case 'usateamspirit.com':
			case 'usarmy.mil':
			case 'usavingsbank.com':
			case 'usakarateri.com':
			case 'usachoice.net':
			case 'usapathway.com':
			case 'usacoil.com':
			case 'usa-skating.com':
			case 'usapcpros.com':
			case 'usasset.com':
			case 'usafp.us':
			case 'usarmyjrotc.com':
			case 'usa.dupont.com':
			case 'usarice.com':
			case 'usacares.org':
			case 'usa829.org':
			case 'usamachinery.com':
			case 'usacann.com':
			case 'usadancedirectory.com':
			case 'usa-4u.com':
			case 'usavgroup.com':
			case 'usahockey.org':
			case 'usahostels.com':
			case 'usamobility.com':
			case 'usaelg.com':
			case 'usafleamarkets.com':
			case 'usatoday.com':
			case 'usamontana.com':
			case 'usavepharmacy.com':
			case 'usaautocenter.com':
			case 'usau.com':
			case 'usaautosales.net':
			case 'usadistillers.com':
			case 'usa.norgren.com':
			case 'usaverx.com':
			case 'usanet.net':
			case 'usajohn.com':
			case 'usa-american.net':
			case 'usaccessconsultants.com':
			case 'usafieldhockey.com':
			case 'usatrailers.com':
			case 'usarchitectsmuncie.com':
			case 'usaenduranceevents.com':
			case 'usa.striata.com':
			case 'usawarvet.org':
			case 'usaind.com':
			case 'usask.ca':
			case 'usatek.net':
			case 'usaflags.com':
			case 'usacomminc.com':
			case 'usalc.net':
			case 'usairportparking.com':
			case 'usao.edu':
			case 'usainc.org':
			case 'usaclean.com':
			case 'usachampion.com':
			case 'usagypsum.com':
			case 'usaverents.com':
			case 'usatankwash.com':
			case 'usa.redcross.org':
			case 'usairm.com':
			case 'usamotorhost.com':
			case 'usautomationcontrols.com':
			case 'mailers.co.uk':
			case 'mail-bip.com':
			case 'mail.ee':
			case 'mail.wowmail.com':
			case 'mail.dk':
			case 'mailoans.biz':
			case 'mail4millers.com':
			case 'mail.nu':
			case 'mail.sgigrain.com					':
			case 'mail.tcitys.org':
			case 'mail.freelotto.com':
			case 'mail.valenciacollege.edu':
			case 'mailamerica.com':
			case 'mail2wonder.com':
			case 'mailc.net':
			case 'mailcbi.com':
			case 'mail.arizona.edu':
			case 'mailpsk.com':
			case 'mailaka.net':
			case 'mail.conmed.com':
			case 'mailliard.com':
			case 'mailbox.winthrop.edu':
			case 'mail.globalair.com':
			case 'mail.yu.edu':
			case 'mailme.dk':
			case 'mail2senegal.com':
			case 'mail.adsl4less.com':
			case 'mail.ri.net':
			case 'mailshack.com':
			case 'mail.csky.net':
			case 'maillie.com':
			case 'mailcruiser.keene.edu':
			case 'mail.uoguelph.ca':
			case 'mail.kearsley.k12.mi.us':
			case 'mail.rrdsb.com':
			case 'mail.utexas.edu':
			case 'mailbush.com':
			case 'mailmarketingfla.com':
			case 'mail.enr6.k12.mo.us':
			case 'mailmanagerinc.com':
			case 'mail.kvcc.edu':
			case 'mail.mhcdc.org':
			case 'mailbox2000.net':
			case 'mail.vu':
			case 'mailni.com':
			case 'mail.pt':
			case 'mailwagner.com':
			case 'mail.magee.edu':
			case 'mailpresort.com':
			case 'mail2expert.com':
			case 'mailprofiler.com':
			case 'mail.aimhigh.net':
			case 'mail.midsouthcc.edu':
			case 'mail.ie':
			case 'mail.uniontown.k12.pa.us':
			case 'mail2.lcia.com':
			case 'mail.com':
			case 'gmx.com':
			case 'gmx.us':
			case 'email.msn.com':
			case 'mail.org':
			case 'mail.uc.edu':
			case 'email.uc.edu':
			case 'email.unc.edu':
			case 'mail.co':
			case 'mail.umsl.edu':
			case 'email.sc.edu':
			case 'email.arizona.edu':
			case 'email.vccs.edu':
			case 'EMAIL.CHOP.EDU':
			case 'email.ws':
			case 'mail.com':
			case 'email.com':
			case 'email.us':
			case 'mail.ru':
			case 'mail.dc.state.fl.us':
			case 'mail-me.com':
			case 'mail.broward.edu':
			case 'mailout10.bedhash.com':
			case 'mail2go.com':
			case 'mailanthus.com':
			case 'mail-lb2-int.dca2.superb.net':
			case 'mail.ccsf.edu':
			case 'mail.usa.com':
			case 'mail.twu.edu':
			case 'mail.usmagazine.com':
			case 'mailbox.swipnet.se':
			case 'mail.lipscomb.edu':
			case 'mailsamolatina.com':
			case 'mail.fcboe.org':
			case 'mailcity.com':
			case 'mail.mil':
			case 'mail137.subscribermail.com':
			case 'mail23.dataentry-jobs.net':
			case 'mail2world.com':
			case 'mail2greece.com':
			case 'mail2think.com':
			case 'mail.wlu.edu':
			case 'mail.martinmethodist.edu':
			case 'mail.gvsu.edu':
			case 'mail.sio.midco.net':
			case 'mail2angela.com':
			case 'mailshipsolutions.com':
			case 'mail.cngold.org':
			case 'mail.lrm2.k12.wy.us':
			case 'mail2joseph.com':
			case 'mail.clxcpu.com':
			case 'mail.gatech.edu':
			case 'mail.kmutnb.ac.th':
			case 'mail.maehdros.be':
			case 'mail.networksolutionsemail':
			case 'mail.pioneeris.net':
			case 'mailpod.hostingplatform.co':
			case 'mailrelay4.gazprom.ru':
			case 'mail.plymouth.edu':
			case 'mail.mg-advertising.net':
			case 'mail.mn':
			case 'mail2maxwell.com':
			case 'mailer.com':
			case 'mail.missouri.edu':
			case 'mail2cool.com':
			case 'mail.uajy.ac.id':
			case 'mailcan.com':
			case 'mail333.com':
			case 'mailbag.com':
			case 'mail15.com':
			case 'mailtorch.com':
			case 'mailas.com':
			case 'mail.mvnu.edu':
			case 'mail.wccaheadstart.org':
			case 'mail.usi.edu':
			case 'mail.med.upenn.edu':
			case 'mailrelay2.gazprom.ru':
			case 'mail.tascom.ru':
			case 'mail.internetseer.com':
			case 'mail.hamiltontn.gov':
			case 'mail.okaloosa.k12.fl.us':
			case 'mailbox.sc.edu':
			case 'mail.clayton.edu':
			case 'mailcc.com':
			case 'mail.smu.edu':
			case 'mail.barry.edu':
			case 'mailhost.polarhome.com':
			case 'mailcui.com':
			case 'mail.roanoke.edu':
			case 'mail.bbahranice.cz':
			case 'mail-in5-pp.ewetel.de':
			case 'mailblc.org':
			case 'mail2art.com':
			case 'mail.kz':
			case 'mail.slh.wisc.edu':
			case 'mail.tpchel.ru':
			case 'mail.1stcml.com':
			case 'mail.kingsizedirect.com':
			case 'mail1.stofanet.dk':
			case 'mail.davenport.k12.ia.us':
			case 'mail.hotmail.com':
			case 'mail2michelle.com':
			case 'mail.auis.net':
			case 'mailbolt.con':
			case 'mail.bg':
			case 'mailbolt.com':
			case 'mail.bubblers.k12.pa.us':
			case 'mail.ftc.org':
			case 'mail4y.com':
			case 'mail.mhanet.com':
			case 'mail.bw.edu':
			case 'mail.net':
			case 'mail.manti.com':
			case 'mailinator.com':
			case 'mail.amc.edu':
			case 'mail.chpaman.edu':
			case 'mail.usf.edu':
			case 'mail.atlantisadventures.co':
			case 'maildiablo.com':
			case 'mail2web.com':
			case 'mail2usa.com':
			case 'mail2cowgirl.com':
			case 'mailsnare.net':
			case 'mail2cute.com':
			case 'mail2queen.com':
			case 'mailworksllc.com':
			case 'mail.cm':
			case 'mail2doris.com':
			case 'mail2christy.com':
			case 'mail.etsu.edu':
			case 'mail.montclair.edu':
			case 'mail.bradley.edu':
			case 'mailexcite.com':
			case 'mailbox.net':
			case 'mailpac.net':
			case 'mail.orbitel.bg':
			case 'mail.easynet.co.uk':
			case 'mail.telepac.pt':
			case 'mailattache.com':
			case 'mailonline.com':
			case 'mail.mylife.com':
			case 'mail.mst.edu':
			case 'mail.md':
			case 'mailmt.com':
			case 'mail.saturnfans.com':
			case 'mail.fr':
			case 'mail.stmarytx.edu':
			case 'mail.fresnostate.edu':
			case 'mailstation.com':
			case 'mail.cim':
			case 'mail.dccc.edu':
			case 'mail.om':
			case 'mail.wvu.edu':
			case 'mail.dumas-k12.net':
			case 'mail.chattanooga.gov':
			case 'mailer.fsu.edu':
			case 'mailtothis.com':
			case 'mail.k12.tn.us':
			case 'mail.cccd.edu':
			case 'mailboxprintandmail.com':
			case 'mail.uri.edu':
			case 'mail.oratoryschools.org':
			case 'mail.guam.net':
			case 'mail.xj-n-tax.gov.cn':
			case 'mail.ubc.ca':
			case 'mail.ci.lubbock.tx.us':
			case 'mail.roosevelt.edu':
			case 'mail.vresp.com':
			case 'mailtag.com':
			case 'mailpuppy.com':
			case 'mail.riverview.net':
			case 'mail2dancer.com':
			case 'mail.house.gov':
			case 'mail.afats.khc.edu.tw':
			case 'mail.ranbowintl.com':
			case 'mail.va':
			case 'mailde.de':
			case 'mailjmc.com':
			case 'mail.siom.ac.cn':
			case 'mail.widener.edu':
			case 'mailchimp.com':
			case 'mail.lced.net':
			case 'mail.goucher.edu':
			case 'maila.com':
			case 'mail2.dpa.de':
			case 'mailstop.com':
			case 'mailstop.co':
			case 'mailzeta.com':
			case 'mailandbusiness.com':
			case 'mailsitedirect.us':
			case 'mail.mizzou.edu':
			case 'mailtous.com':
			case 'mail.mccneb.edu':
			case 'mailstjohn.com':
			case 'mail.depaul.edu':
			case 'mailboxgenie.com':
			case 'mail.umkc.edu':
			case 'mail-nbf.kaydon.com':
			case 'mail.funtime-parts.de':
			case 'mail.glassdoctor.c':
			case 'mail.gtc.edu':
			case 'mailmalaga.com':
			case 'mail.raonoke.edu':
			case 'mail.csdnet.com.ar':
			case 'mailbox-3.com':
			case 'mailimate.com':
			case 'mail.fshu.edu':
			case 'mail.usciences.edu':
			case 'mail.sy':
			case 'mail.mpt.org':
			case 'mailhaven.com':
			case 'mail.shcnc.ac.cn':
			case 'mailtoko.com':
			case 'mail.shu.edu.cn':
			case 'mail.buffalostate.edu':
			case 'mail.mailinfinity.com':
			case 'mailinfinity.com':
			case 'mailturk.net':
			case 'mail.cfcc.edu':
			case 'mails.thu.edu.cn':
			case 'mail.mauricionassau.com.br':
			case 'mail.epc.k12.ar.us':
			case 'mail.hellosign.com':
			case 'mailpro1.com':
			case 'mail.kana.k12.wv.us':
			case 'mail54.fssprus.ru':
			case 'mailtmi.com':
			case 'mail.pf':
			case 'mailmix.pl':
			case 'maildrop.cc':
			case 'mail2.thejakartapost.com':
			case 'mailance.com':
			case 'mailcentrals.co.uk':
			case 'mail.xom':
			case 'mail4me.com':
			case 'mail2games.com':
			case 'mail.utoronto.ca':
			case 'mail.yzu.edu.tw':
			case 'mailsystem.au':
			case 'mail.unkc.edu':
			case 'mail-a.tvnetwork.hu':
			case 'mailfa.com':
			case 'mailismagic.com':
			case 'mail.nwmissouri.edu':
			case 'mail.c':
			case 'mail.lcc.edu':
			case 'mail.sunysuffolk.edu':
			case 'mail-efax.co.uk':
			case 'mail.cin':
			case 'mailbox.hu':
			case 'mail.blueonyx.it':
			case 'mailforspam.com':
			case 'maill.cim':
			case 'mailserver.i-next.psi.br':
			case 'maill.com':
			case 'mail.tt':
			case 'mail.fruitvaleisd.com':
			case 'mail.hz.zj.cn':
			case 'mail.winchart.com.tw':
			case 'mail.redzone.com.au':
			case 'mail.la-archdiocese.net':
			case 'mail.extravaganza-sweepstakes.co':
			case 'mail.xmission.com':
			case 'mailfw.whoisproxy.com':
			case 'mail.weber.edu':
			case 'mail.missouri.mail':
			case 'mail.lcs.net':
			case 'mail.ap-hm.fr':
			case 'mail.sogo.com.tw':
			case 'mail.c8m':
			case 'mail.cam':
			case 'mail.law.cuny.edu':
			case 'mail.hzic.edu.cn':
			case 'mail174.bizfree.kr':
			case 'mailbag.net':
			case 'mail.gmail.com':
			case 'mail2go.net':
			case 'mail.westco.net':
			case 'mail.endicott.edu':
			case 'mail.tmccentral.org':
			case 'mailoo.org':
			case 'mail.cmich.edu':
			case 'maildsi.com':
			case 'mail.austria.com':
			case 'mailplex.com':
			case 'mailtrace.cjb.net':
			case 'mail.sdsu.edu':
			case 'mail.sfsu.edu':
			case 'mailspot.org':
			case 'mail.cstudies.ubc.ca':
			case 'mails-gw.fnbs.net.my':
			case 'mail.ustc.edu.cn':
			case 'mail.wlc.edu':
			case 'mail.ms.maquoketa.k12.ia.us':
			case 'mail.scut.edu.cn':
			case 'mail.gardensnyc.net':
			case 'mailfrom.ru':
			case 'mail12.world4you.com':
			case 'mail.mira.dk':
			case 'mail.iccfa.com':
			case 'mail.sacredheart.edu':
			case 'mailbox99.net':
			case 'mail.darton.edu':
			case 'mail.ts-zipper.com.tw':
			case 'mail-exit-bc.narrowsui':
			case 'mail-bullet-hubs.sicens.net':
			case 'mailtcs.com':
			case 'mailrsi.com':
			case 'mailtpa.com':
			case 'mailnull.com':
			case 'mail.mcgill.ca':
			case 'mail.chapman.edu':
			case 'mailcatch.com':
			case 'mail.nih.gov':
			case 'mail.nplindia.ernet.in':
			case 'mail.oac.uncor.edu':
			case 'mail.nccu.edu':
			case 'mail2star.com':
			case 'mailserver.surfpacific.co':
			case 'mail.enet.com.':
			case 'mail.warp.co.nz':
			case 'mail.china.cn':
			case 'mail.rmu.edu':
			case 'mailaaa.com':
			case 'mail2king.com':
			case 'mail.worcester.k12.md.us':
			case 'mailprint.com':
			case 'mailpring.com':
			case 'mail.exis.it':
			case 'mail.aim-net.mx':
			case 'mail.nist.ro':
			case 'mail.rb.ru':
			case 'mail2ronald.com':
			case 'mail3.newulmtel.net':
			case 'mailboxz.net':
			case 'mail.primelsolutions.com':
			case 'mail9.ufmg.br':
			case 'mail.fhsu.edu':
			case 'mail.jkes.tp.edu.tw':
			case 'mail.boonty.com':
			case 'mail.casapellas.com.ni':
			case 'mail.ro':
			case 'mail.umw.edu':
			case 'mailumsl.edu':
			case 'mail.win.org':
			case 'mail.regent.edu':
			case 'mailfrhub.com':
			case 'mail.hocking.edu':
			case 'mail.plattsburgh.edu':
			case 'mail.techno-link.com':
			case 'mail.flyon.net':
			case 'mail.wvncc.edu':
			case 'mail2roy.com':
			case 'mailmeonline.net':
			case 'mailfern.com':
			case 'mail.techno-linc.com':
			case 'mail.ab.mec.edu':
			case 'usa.com':
			case 'usa.co':
			case 'usa.net':
			case 'usadig.com':
			case 'usa-spirit.com':
			case 'usabiz.biz':
			case 'usahealthvip.com':
			case 'usalaptoprepair.com':
			case 'usalendinginc.com':
			case 'usa2net.net':
			case 'usa-companies.net':
			case 'usagis-house.net':
			case 'usadma.com':
			case 'usamortgageinc.com':
			case 'usawide.net':
			case 'usamedia.tv':
			case 'usadutchinc.com':
			case 'usace.army.mil':
			case 'usa-labor.com':
			case 'usashs.com':
			case 'usadv.com':
			case 'usainnmotel.com':
			case 'usabg.net':
			case 'usasia-ins.com':
			case 'usabowman.com':
			case 'usana.com':
			case 'usanfsc.com':
			case 'usa.comif':
			case 'usadvantage.com':
			case 'usa-empire.com':
			case 'usastaffingnetwork.com':
			case 'usairlinepilots.org':
			case 'usableinterface.com':
			case 'usasend.com':
			case 'usa-networking.com':
			case 'usaprotection.com':
			case 'usa.comr':
			case 'usadrivers.com':
			case 'usahealth.edu':
			case 'usarmyvelo-1.com':
			case 'usabras.com':
			case 'usamedicus.com':
			case 'usalp.org':
			case 'usap.com':
			case 'usafricasynergy.org':
			case 'usadjustingservices.net':
			case 'usairways.com':
			case 'usa.varioline.com':
			case 'usaabs.com':
			case 'usainternationaldata.com':
			case 'usabioproducts.com':
			case 'usa2net.nert':
			case 'usalimoandsedan.com':
			case 'usahandcrafted.com':
			case 'usabel.com':
			case 'usagainstgreed.org':
			case 'usa.g4s.com':
			case 'usafa.edu':
			case 'usa.xerox.com':
			case 'usascheduler.com':
			case 'usaircraftsales.com':
			case 'usabilitycounts.com':
			case 'usatechsearch.com':
			case 'usa-travel-agency.com':
			case 'usafill.com':
			case 'usa-shade.com':
			case 'usa.cipom':
			case 'usaforever.net':
			case 'usadmail.com':
			case 'usatco.co.uk':
			case 'usa-security.net':
			case 'usa-11.com':
			case 'usace.amry.mil':
			case 'usaninc.com':
			case 'usaalarmsystems.com':
			case 'usa.apachecorp.com':
			case 'usa.ibs.org':
			case 'usaprivatesecurity.com':
			case 'usalendingandrealty.com':
			case 'usataxiandlimo.com':
			case 'usamusiic.org':
			case 'usafloortec.com':
			case 'usaddress.us':
			case 'usa.cm':
			case 'usarecyclingcenters.com':
			case 'usa-tsg.com':
			case 'usaswim.com':
			case 'usacracing.com':
			case 'usaaap.com':
			case 'usautomationcorp.com':
			case 'usaviator.net':
			case 'usaa.com':
			case 'usapromotionalcards.com':
			case 'usateamspirit.com':
			case 'usarmy.mil':
			case 'usavingsbank.com':
			case 'usakarateri.com':
			case 'usachoice.net':
			case 'usapathway.com':
			case 'usacoil.com':
			case 'usa-skating.com':
			case 'usapcpros.com':
			case 'usasset.com':
			case 'usafp.us':
			case 'usarmyjrotc.com':
			case 'usa.dupont.com':
			case 'usarice.com':
			case 'usacares.org':
			case 'usa829.org':
			case 'usamachinery.com':
			case 'usacann.com':
			case 'usadancedirectory.com':
			case 'usa-4u.com':
			case 'usavgroup.com':
			case 'usahockey.org':
			case 'usahostels.com':
			case 'usamobility.com':
			case 'usaelg.com':
			case 'usafleamarkets.com':
			case 'usatoday.com':
			case 'usamontana.com':
			case 'usavepharmacy.com':
			case 'usaautocenter.com':
			case 'usau.com':
			case 'usaautosales.net':
			case 'usadistillers.com':
			case 'usa.norgren.com':
			case 'usaverx.com':
			case 'usanet.net':
			case 'usajohn.com':
			case 'usa-american.net':
			case 'usaccessconsultants.com':
			case 'usafieldhockey.com':
			case 'usatrailers.com':
			case 'usarchitectsmuncie.com':
			case 'usaenduranceevents.com':
			case 'usa.striata.com':
			case 'usawarvet.org':
			case 'usaind.com':
			case 'usask.ca':
			case 'usatek.net':
			case 'usaflags.com':
			case 'usacomminc.com':
			case 'usalc.net':
			case 'usairportparking.com':
			case 'usao.edu':
			case 'usainc.org':
			case 'usaclean.com':
			case 'usachampion.com':
			case 'usagypsum.com':
			case 'usaverents.com':
			case 'usatankwash.com':
			case 'usa.redcross.org':
			case 'usairm.com':
			case 'usamotorhost.com':
			case 'usautomationcontrols.com':
			case 'mailers.co.uk':
			case 'mail-bip.com':
			case 'mail.ee':
			case 'mail.wowmail.com':
			case 'mail.dk':
			case 'mailoans.biz':
			case 'mail4millers.com':
			case 'mail.nu':
			case 'mail.sgigrain.com':
			case 'mail.tcitys.org':
			case 'mail.freelotto.com':
			case 'mail.valenciacollege.edu':
			case 'mailamerica.com':
			case 'mail2wonder.com':
			case 'mailc.net':
			case 'mailcbi.com':
			case 'mail.arizona.edu':
			case 'mailpsk.com':
			case 'mailaka.net':
			case 'mailliard.com':
			case 'mailbox.winthrop.edu':
			case 'mail.globalair.com':
			case 'mail.yu.edu':
			case 'mailme.dk':
			case 'mail2senegal.com':
			case 'mail.adsl4less.com':
			case 'mail.ri.net':
			case 'mailshack.com':
			case 'mail.csky.net':
			case 'maillie.com':
			case 'mailcruiser.keene.edu':
			case 'mail.uoguelph.ca':
			case 'mail.kearsley.k12.mi.us':
			case 'mail.rrdsb.com':
			case 'mail.utexas.edu':
			case 'mailbush.com':
			case 'mailmarketingfla.com':
			case 'mail.enr6.k12.mo.us':
			case 'mailmanagerinc.com':
			case 'mail.kvcc.edu':
			case 'mail.mhcdc.org':
			case 'mailbox2000.net':
			case 'mail.vu':
			case 'mailni.com':
			case 'mail.pt':
			case 'mailwagner.com':
			case 'mail.magee.edu':
			case 'mailpresort.com':
			case 'mail2expert.com':
			case 'mailprofiler.com':
			case 'mail.aimhigh.net':
			case 'mail.midsouthcc.edu':
			case 'mail.ie':
			case 'mail.uniontown.k12.pa.us':
			case 'mail2.lcia.com':
			{
				fputs($fileGmxUSA,$line);
			}
			break;
		  
		  
		  
		  case 'yahoo.com':
		  case 'ymail.com':
		  case 'rocketmail.com':
		  case 'yahoo.co.uk':
		  {
		    fputs($fileYahooUSA,$line);
		  }
		  break;
		 
	  }
	}
}

else
{
    while($line = fgets($file))
	{
		$line      = strtolower($line);
	    $delimiter = $_POST['cmbDelimiter'];
		$indexEmail = $_POST['indexEmail'];
	    $split = explode($delimiter,trim($line));
		$email = $split[$indexEmail-1];
		$splitEmail = explode('@',$email);
		
		switch(strtolower($splitEmail[1]))
		{
			
			//Talk Talk :
			case'homecall.co.uk':
			case'talktalk.net':
			case'lineone.net':
			case'toucansurf.com':
			case'dsl.pipex.com':
			case'tinyworld.co.uk':
			case'tiscali.co.uk':
			case'onetel.com':
			case'ukgateway.net':
			case'tinyonline.co.uk':
			case'dial.pipex.com':
			case'worldonline.co.uk':
			case'screaming.net':	
			{
				fputs($filetalktalk,$line);
			}
			break;
			
			
		
			
			//AOL USA : 
			case 'aol.com':
			case 'aol.net':
			case 'aim.com':
			case 'aim.net':
			case 'cs.com':
			case 'cs.net':
			case 'netscape.com':
			case 'netscape.net':
			case 'compuserve.com':
			case 'wmconnect.com':
			case 'luckymail.com':
			{
				fputs($fileAolUsa,$line);
			}
			break;
		
		
			//AOL UK : 
			case 'aol.co.uk':
			case 'aol.com':
			case 'aol.net':
			case 'aim.com':
			case 'aim.net':
			case 'cs.com':
			case 'cs.net':
			case 'netscape.com':
			case 'netscape.net':
			case 'compuserve.com':
			case 'wmconnect.com':
			case 'luckymail.com':
			{
				fputs($fileAolUK,$line);
			}
			break;
		   
		   
			//Virgin Media :
			case 'virgin.net':
			case 'virginmedia.com':
			case 'virginmedia.co.uk':
			case 'virginbroadband.com.au':
			case 'sfr.fr':
		    case 'club-internet.fr':
		    case 'neuf.fr':
			{
				fputs($filevirginUK,$line);
			}
			break;
		   
		   
		   
		   
		   
		   case 'gmail.com':
		 case 'googlemail.com':
		 {
		    fputs($fileGmailUsa,$line);
		 }
		 break;
		   
				case 'att.net':
			 {
				fputs($fileATT,$line);
			 }
			 break;
			 
		  case 'bellsouth.net':
		 {
		    fputs($filebellsouth,$line);
		 }
		 break;
		  
		 case 'rr.com': 
		 case 'roadrunner.com':
		 {
		    fputs($fileroadrunner,$line);
		 }
		 break;
		 
		 
		   	  case 'sbcglobal.net':
		 {
		    fputs($filesbcglobal,$line);
		 }
		 
		 
		   break;
		 
		  case 'juno.net':
		  case 'juno.com':
		 {
		    fputs($filejuno,$line);
		 }
		 
		 
		  break;
		 
		  case 'netzero.com':
		  case 'netzero.net':
		 {
		    fputs($filenetzero,$line);
		 }
		 
		 break;
		 
		  case 'btopenworld.com':
		  case 'btinternet.net':
		  case 'btinternet.com':
		 {
		    fputs($filebtinternet,$line);
		 }
		 
		  break;
		  
		  
		  
		 case 'verizon.net':
		  case 'verizon.com':
		 {
		    fputs($fileverizon,$line);
		 }
		 
		 break;
		   
		   case 'icloud.com':
		   case 'mac.com':
		   {
		     fputs($fileiCloud,$line);
		   }
		   break;
		   
		   
		   
			//Hotmail USA :  
			case 'hotmail.com':
			case 'msn.com':
			case 'live.com':
			case 'outlook.com':
			{
				fputs($fileHotmailUSA,$line);
			}
			break;

		
			//Hotmail UK :
			case 'hotmail.co.uk':
			case 'msn.co.uk':
			case 'live.co.uk':
			case 'outlook.co.uk':
			case 'hotmail.com':
			case 'msn.com':
			case 'live.com':
			case 'outlook.com':
			{
				fputs($fileHotmailUK,$line);
			}
			break;
		
		
			//Hotmail AU :
			case 'hotmail.com.au':
			case 'live.com.au':
			case 'msn.com.au':
			case 'outlook.com.au':
			case 'hotmail.com':
			case 'outlook.com':
			case 'live.com':
			case 'msn.com':
			case 'hotmail.co.uk':
			case 'msn.co.uk':
			case 'live.co.uk':
			case 'outlook.co.uk':
			{
				fputs($fileHotmailAU,$line);
			}
			break;
		   
		   
		   
		   
		   
		 case 'comcast.net':
		 {
		    fputs($fileComcast,$line);
		 }
		 break;
		 
		 
			//GMX : 
			case 'mail.com':
			case 'mail.dc.state.fl.us':
			case 'mail-me.com':
			case 'mail.broward.edu':
			case 'mailout10.bedhash.com':
			case 'mail2go.com':
			case 'mailanthus.com':
			case 'mail.comx':
			case 'mail.ru':
			case 'mail-lb2-int.dca2.superb.net':
			case 'mail.ccsf.edu':
			case 'mail.usa.com':
			case 'mail.twu.edu':
			case 'mail.usmagazine.com':
			case 'mailbox.swipnet.se':
			case 'mail.lipscomb.edu':
			case 'mailsamolatina.com':
			case 'mail.fcboe.org':
			case 'mailcity.com':
			case 'mail.comp':
			case 'mail.mil':
			case 'mail137.subscribermail.com':
			case 'mail23.dataentry-jobs.net':
			case 'mail2world.com':
			case 'mail2greece.com':
			case 'mail2think.com':
			case 'mail.wlu.edu':
			case 'mail.uc.edu':
			case 'mail.martinmethodist.edu':
			case 'mail.gvsu.edu':
			case 'mail.sio.midco.net':
			case 'mail2angela.com':
			case 'mailshipsolutions.com':
			case 'mail.cngold.org':
			case 'mail.lrm2.k12.wy.us':
			case 'mail2joseph.com':
			case 'mail.clxcpu.com':
			case 'mail.gatech.edu':
			case 'mail.kmutnb.ac.th':
			case 'mail.maehdros.be':
			case 'mail.networksolutionsemail.com':
			case 'mail.pioneeris.net':
			case 'mailpod.hostingplatform.com':
			case 'mailrelay4.gazprom.ru':
			case 'mail.plymouth.edu':
			case 'mail.mg-advertising.net':
			case 'mail.mn':
			case 'mail2maxwell.com':
			case 'mailer.com':
			case 'mail.missouri.edu':
			case 'mail2cool.com':
			case 'mail.uajy.ac.id':
			case 'mailcan.com':
			case 'mail333.com':
			case 'mailbag.com':
			case 'mail15.com':
			case 'mailtorch.com':
			case 'mailas.com':
			case 'mail.mvnu.edu':
			case 'mail.wccaheadstart.org':
			case 'mail.usi.edu':
			case 'mail.med.upenn.edu':
			case 'mailrelay2.gazprom.ru':
			case 'mail.tascom.ru':
			case 'mail.internetseer.com':
			case 'mail.org':
			case 'mail.hamiltontn.gov':
			case 'mail.okaloosa.k12.fl.us':
			case 'mailbox.sc.edu':
			case 'mail.clayton.edu':
			case 'mailcc.com':
			case 'mail.smu.edu':
			case 'mail.barry.edu':
			case 'mailhost.polarhome.com':
			case 'mailcui.com':
			case 'mail.roanoke.edu':
			case 'mail.bbahranice.cz':
			case 'mail-in5-pp.ewetel.de':
			case 'mail.com.com':
			case 'mailblc.org':
			case 'mail2art.com':
			case 'mail.kz':
			case 'mail.slh.wisc.edu':
			case 'mail.tpchel.ru':
			case 'mail.1stcml.com':
			case 'mail.kingsizedirect.com':
			case 'mail1.stofanet.dk':
			case 'mail.davenport.k12.ia.us':
			case 'mail.hotmail.com':
			case 'mail2michelle.com':
			case 'mail.auis.net':
			case 'mailbolt.con':
			case 'mail.bg':
			case 'mailbolt.com':
			case 'mail.bubblers.k12.pa.us':
			case 'mail.ftc.org':
			case 'mail4y.com':
			case 'mail.cox.smu.edu':
			case 'mail.mhanet.com':
			case 'mail.bw.edu':
			case 'mail.net':
			case 'mail.manti.com':
			case 'mail.comn':
			case 'mailinator.com':
			case 'mail.amc.edu':
			case 'mail.chpaman.edu':
			case 'mail.usf.edu':
			case 'mail.atlantisadventures.com':
			case 'maildiablo.com':
			case 'mail2web.com':
			case 'mail2usa.com':
			case 'mail2cowgirl.com':
			case 'mailsnare.net':
			case 'mail2cute.com':
			case 'mail2queen.com':
			case 'mailworksllc.com':
			case 'mail.cm':
			case 'mail2doris.com':
			case 'mail2christy.com':
			case 'mail.etsu.edu':
			case 'mail.montclair.edu':
			case 'mail.bradley.edu':
			case 'mailexcite.com':
			case 'mailbox.net':
			case 'mail.comspeculation':
			case 'mail.comff':
			case 'mailpac.net':
			case 'mail.orbitel.bg':
			case 'mail.easynet.co.uk':
			case 'mail.telepac.pt':
			case 'mailattache.com':
			case 'mailonline.com':
			case 'mail.mylife.com':
			case 'mail.mst.edu':
			case 'mail.md':
			case 'mailmt.com':
			case 'mail.saturnfans.com':
			case 'mail.coloradomtn.edu':
			case 'mail.fr':
			case 'mail.stmarytx.edu':
			case 'mail.fresnostate.edu':
			case 'mailstation.com':
			case 'mail.cim':
			case 'mail.dccc.edu':
			case 'mail.om':
			case 'mail.wvu.edu':
			case 'mail.dumas-k12.net':
			case 'mail.chattanooga.gov':
			case 'mailer.fsu.edu':
			case 'mailtothis.com':
			case 'mail.k12.tn.us':
			case 'mail.cccd.edu':
			case 'mailboxprintandmail.com':
			case 'mail.uri.edu':
			case 'mail.oratoryschools.org':
			case 'mail.cocke.k12.tn.us':
			case 'mail.guam.net':
			case 'mail.xj-n-tax.gov.cn':
			case 'mail.ubc.ca':
			case 'mail.ci.lubbock.tx.us':
			case 'mail.roosevelt.edu':
			case 'mail.vresp.com':
			case 'mailtag.com':
			case 'mailpuppy.com':
			case 'mail.riverview.net':
			case 'mail.umsl.edu':
			case 'mail2dancer.com':
			case 'mail.house.gov':
			case 'mail.afats.khc.edu.tw':
			case 'mail.ranbowintl.com':
			case 'mail.co':
			case 'mail.va':
			case 'mailde.de':
			case 'mailjmc.com':
			case 'mail.siom.ac.cn':
			case 'mail.widener.edu':
			case 'mail.com18':
			case 'mailchimp.com':
			case 'mail.lced.net':
			case 'mail.goucher.edu':
			case 'maila.com':
			case 'mail2.dpa.de':
			case 'mailstop.com':
			case 'mailstop.co':
			case 'mailzeta.com':
			case 'mailandbusiness.com':
			case 'mailsitedirect.us':
			case 'mail.mizzou.edu':
			case 'mailtous.com':
			case 'mail.mccneb.edu':
			case 'mailstjohn.com':
			case 'mail.depaul.edu':
			case 'mailboxgenie.com':
			case 'mail.umkc.edu':
			case 'mail-nbf.kaydon.com':
			case 'mail.funtime-parts.de':
			case 'mail.glassdoctor.com':
			case 'mail.gtc.edu':
			case 'mail.come':
			case 'mailmalaga.com':
			case 'mail.raonoke.edu':
			case 'mail.csdnet.com.ar':
			case 'mailbox-3.com':
			case 'mailimate.com':
			case 'mail.fshu.edu':
			case 'mail.usciences.edu':
			case 'mail.sy':
			case 'mail.mpt.org':
			case 'mailhaven.com':
			case 'mail.shcnc.ac.cn':
			case 'mailtoko.com':
			case 'mail.shu.edu.cn':
			case 'mail.buffalostate.edu':
			case 'mail.mailinfinity.com':
			case 'mailinfinity.com':
			case 'mailturk.net':
			case 'mail.cfcc.edu':
			case 'mails.thu.edu.cn':
			case 'mail.mauricionassau.com.br':
			case 'mail.epc.k12.ar.us':
			case 'mail.hellosign.com':
			case 'mailpro1.com':
			case 'mail.kana.k12.wv.us':
			case 'mail54.fssprus.ru':
			case 'mailtmi.com':
			case 'mail.pf':
			case 'mailmix.pl':
			case 'maildrop.cc':
			case 'mail2.thejakartapost.com':
			case 'mailance.com':
			case 'mailcentrals.co.uk':
			case 'mail.xom':
			case 'mail4me.com':
			case 'mail2games.com':
			case 'mail.utoronto.ca':
			case 'mail.yzu.edu.tw':
			case 'mailsystem.au':
			case 'mail.unkc.edu':
			case 'mail-a.tvnetwork.hu':
			case 'mailfa.com':
			case 'mailismagic.com':
			case 'mail.nwmissouri.edu':
			case 'mail.c':
			case 'mail.con':
			case 'mail.lcc.edu':
			case 'mail.sunysuffolk.edu':
			case 'mail-efax.co.uk':
			case 'mail.cin':
			case 'mailbox.hu':
			case 'mail.blueonyx.it':
			case 'mailforspam.com':
			case 'maill.cim':
			case 'mailserver.i-next.psi.br':
			case 'maill.com':
			case 'mail.tt':
			case 'mail.fruitvaleisd.com':
			case 'mail.hz.zj.cn':
			case 'mail.winchart.com.tw':
			case 'mail.comam':
			case 'mail.redzone.com.au':
			case 'mail.la-archdiocese.net':
			case 'mail.extravaganza-sweepstakes.com':
			case 'mail.xmission.com':
			case 'mailfw.whoisproxy.com':
			case 'mail.couk':
			case 'mail.weber.edu':
			case 'mail.missouri.mail':
			case 'mail.lcs.net':
			case 'mail.ap-hm.fr':
			case 'mail.sogo.com.tw':
			case 'mail.c8m':
			case 'mail.cam':
			case 'mail.law.cuny.edu':
			case 'mail.hzic.edu.cn':
			case 'mail174.bizfree.kr':
			case 'mailbag.net':
			case 'mail.gmail.com':
			case 'mail2go.net':
			case 'mail.westco.net':
			case 'mail.endicott.edu':
			case 'mail.tmccentral.org':
			case 'mailoo.org':
			case 'mail.cmich.edu':
			case 'maildsi.com':
			case 'mail.austria.com':
			case 'mailplex.com':
			case 'mailtrace.cjb.net':
			case 'mail.sdsu.edu':
			case 'mail.sfsu.edu':
			case 'mailspot.org':
			case 'mail.cstudies.ubc.ca':
			case 'mails-gw.fnbs.net.my':
			case 'mail.ustc.edu.cn':
			case 'mail.wlc.edu':
			case 'mail.ms.maquoketa.k12.ia.us':
			case 'mail.scut.edu.cn':
			case 'mail.gardensnyc.net':
			case 'mailfrom.ru':
			case 'mail12.world4you.com':
			case 'mail.mira.dk':
			case 'mail.iccfa.com':
			case 'mail.sacredheart.edu':
			case 'mailbox99.net':
			case 'mail.darton.edu':
			case 'mail.ts-zipper.com.tw':
			case 'mail-exit-bc.narrowsuite.com':
			case 'mail-bullet-hubs.sicens.net':
			case 'mailtcs.com':
			case 'mail.co.uk':
			case 'mailrsi.com':
			case 'mailtpa.com':
			case 'mailnull.com':
			case 'mail.mcgill.ca':
			case 'mail.chapman.edu':
			case 'mailcatch.com':
			case 'mail.nih.gov':
			case 'mail.nplindia.ernet.in':
			case 'mail.oac.uncor.edu':
			case 'mail.nccu.edu':
			case 'mail2star.com':
			case 'mailserver.surfpacific.co':
			case 'mail.enet.com.cn':
			case 'mail.cometc':
			case 'mail.warp.co.nz':
			case 'mail.china.cn':
			case 'mail.rmu.edu':
			case 'mailaaa.com':
			case 'mail2king.com':
			case 'mail.worcester.k12.md.us':
			case 'mailprint.com':
			case 'mailpring.com':
			case 'mail.exis.it':
			case 'mail.aim-net.mx':
			case 'mail.nist.ro':
			case 'mail.rb.ru':
			case 'mail2ronald.com':
			case 'mail3.newulmtel.net':
			case 'mailboxz.net':
			case 'mail.primelsolutions.com':
			case 'mail9.ufmg.br':
			case 'mail.coming':
			case 'mail.fhsu.edu':
			case 'mail.jkes.tp.edu.tw':
			case 'mail.boonty.com':
			case 'mail.casapellas.com.ni':
			case 'mail.ro':
			case 'mail.umw.edu':
			case 'mailumsl.edu':
			case 'mail.win.org':
			case 'mail.regent.edu':
			case 'mailfrhub.com':
			case 'mail.comcast.net':
			case 'mail.comhot':
			case 'mail.comi':
			case 'mail.comg':
			case 'mail.comomfice':
			case 'mail.comro':
			case 'mail.comtasea':
			case 'mail.com.np':
			case 'mail.hocking.edu':
			case 'mail.plattsburgh.edu':
			case 'mail.techno-link.com':
			case 'mail.flyon.net':
			case 'mail.wvncc.edu':
			case 'mail2roy.com':
			case 'mailmeonline.net':
			case 'mailfern.com':
			case 'mail.techno-linc.com':
			case 'mail.ab.mec.edu':
			case 'usa.com':
			case 'usa.co':
			case 'usa.net':
			case 'usadig.com':
			case 'usa-spirit.com':
			case 'usabiz.biz':
			case 'usahealthvip.com':
			case 'usalaptoprepair.com':
			case 'usalendinginc.com':
			case 'usa2net.net':
			case 'usa-companies.net':
			case 'usagis-house.net':
			case 'usadma.com':
			case 'usamortgageinc.com':
			case 'usawide.net':
			case 'usamedia.tv':
			case 'usadutchinc.com':
			case 'usace.army.mil':
			case 'usa-labor.com':
			case 'usashs.com':
			case 'usadv.com':
			case 'usainnmotel.com':
			case 'usabg.net':
			case 'usasia-ins.com':
			case 'usabowman.com':
			case 'usana.com':
			case 'usanfsc.com':
			case 'usa.comif':
			case 'usadvantage.com':
			case 'usa-empire.com':
			case 'usastaffingnetwork.com':
			case 'usairlinepilots.org':
			case 'usableinterface.com':
			case 'usasend.com':
			case 'usa-networking.com':
			case 'usaprotection.com':
			case 'usa.comr':
			case 'usadrivers.com':
			case 'usahealth.edu':
			case 'usarmyvelo-1.com':
			case 'usabras.com':
			case 'usamedicus.com':
			case 'usalp.org':
			case 'usap.com':
			case 'usafricasynergy.org':
			case 'usadjustingservices.net':
			case 'usairways.com':
			case 'usa.varioline.com':
			case 'usaabs.com':
			case 'usainternationaldata.com':
			case 'usabioproducts.com':
			case 'usa2net.nert':
			case 'usalimoandsedan.com':
			case 'usahandcrafted.com':
			case 'usabel.com':
			case 'usagainstgreed.org':
			case 'usa.g4s.com':
			case 'usafa.edu':
			case 'usa.xerox.com':
			case 'usascheduler.com':
			case 'usaircraftsales.com':
			case 'usabilitycounts.com':
			case 'usatechsearch.com':
			case 'usa-travel-agency.com':
			case 'usafill.com':
			case 'usa-shade.com':
			case 'usa.cipom':
			case 'usaforever.net':
			case 'usadmail.com':
			case 'usatco.co.uk':
			case 'usa-security.net':
			case 'usa-11.com':
			case 'usace.amry.mil':
			case 'usaninc.com':
			case 'usaalarmsystems.com':
			case 'usa.apachecorp.com':
			case 'usa.ibs.org':
			case 'usaprivatesecurity.com':
			case 'usalendingandrealty.com':
			case 'usataxiandlimo.com':
			case 'usamusiic.org':
			case 'usafloortec.com':
			case 'usaddress.us':
			case 'usa.cm':
			case 'usarecyclingcenters.com':
			case 'usa-tsg.com':
			case 'usaswim.com':
			case 'usacracing.com':
			case 'usaaap.com':
			case 'usautomationcorp.com':
			case 'usaviator.net':
			case 'usaa.com':
			case 'usapromotionalcards.com':
			case 'usateamspirit.com':
			case 'usarmy.mil':
			case 'usavingsbank.com':
			case 'usakarateri.com':
			case 'usachoice.net':
			case 'usapathway.com':
			case 'usacoil.com':
			case 'usa-skating.com':
			case 'usapcpros.com':
			case 'usasset.com':
			case 'usafp.us':
			case 'usarmyjrotc.com':
			case 'usa.dupont.com':
			case 'usarice.com':
			case 'usacares.org':
			case 'usa829.org':
			case 'usamachinery.com':
			case 'usacann.com':
			case 'usadancedirectory.com':
			case 'usa-4u.com':
			case 'usavgroup.com':
			case 'usahockey.org':
			case 'usahostels.com':
			case 'usamobility.com':
			case 'usaelg.com':
			case 'usafleamarkets.com':
			case 'usatoday.com':
			case 'usamontana.com':
			case 'usavepharmacy.com':
			case 'usaautocenter.com':
			case 'usau.com':
			case 'usaautosales.net':
			case 'usadistillers.com':
			case 'usa.norgren.com':
			case 'usaverx.com':
			case 'usanet.net':
			case 'usajohn.com':
			case 'usa-american.net':
			case 'usaccessconsultants.com':
			case 'usafieldhockey.com':
			case 'usatrailers.com':
			case 'usarchitectsmuncie.com':
			case 'usaenduranceevents.com':
			case 'usa.striata.com':
			case 'usawarvet.org':
			case 'usaind.com':
			case 'usask.ca':
			case 'usatek.net':
			case 'usaflags.com':
			case 'usacomminc.com':
			case 'usalc.net':
			case 'usairportparking.com':
			case 'usao.edu':
			case 'usainc.org':
			case 'usaclean.com':
			case 'usachampion.com':
			case 'usagypsum.com':
			case 'usaverents.com':
			case 'usatankwash.com':
			case 'usa.redcross.org':
			case 'usairm.com':
			case 'usamotorhost.com':
			case 'usautomationcontrols.com':
			case 'mailers.co.uk':
			case 'mail-bip.com':
			case 'mail.ee':
			case 'mail.wowmail.com':
			case 'mail.dk':
			case 'mailoans.biz':
			case 'mail4millers.com':
			case 'mail.nu':
			case 'mail.sgigrain.com					':
			case 'mail.tcitys.org':
			case 'mail.freelotto.com':
			case 'mail.valenciacollege.edu':
			case 'mailamerica.com':
			case 'mail2wonder.com':
			case 'mailc.net':
			case 'mailcbi.com':
			case 'mail.arizona.edu':
			case 'mailpsk.com':
			case 'mailaka.net':
			case 'mail.conmed.com':
			case 'mailliard.com':
			case 'mailbox.winthrop.edu':
			case 'mail.globalair.com':
			case 'mail.yu.edu':
			case 'mailme.dk':
			case 'mail2senegal.com':
			case 'mail.adsl4less.com':
			case 'mail.ri.net':
			case 'mailshack.com':
			case 'mail.csky.net':
			case 'maillie.com':
			case 'mailcruiser.keene.edu':
			case 'mail.uoguelph.ca':
			case 'mail.kearsley.k12.mi.us':
			case 'mail.rrdsb.com':
			case 'mail.utexas.edu':
			case 'mailbush.com':
			case 'mailmarketingfla.com':
			case 'mail.enr6.k12.mo.us':
			case 'mailmanagerinc.com':
			case 'mail.kvcc.edu':
			case 'mail.mhcdc.org':
			case 'mailbox2000.net':
			case 'mail.vu':
			case 'mailni.com':
			case 'mail.pt':
			case 'mailwagner.com':
			case 'mail.magee.edu':
			case 'mailpresort.com':
			case 'mail2expert.com':
			case 'mailprofiler.com':
			case 'mail.aimhigh.net':
			case 'mail.midsouthcc.edu':
			case 'mail.ie':
			case 'mail.uniontown.k12.pa.us':
			case 'mail2.lcia.com':
			case 'mail.com':
			case 'gmx.com':
			case 'gmx.us':
			case 'email.msn.com':
			case 'mail.org':
			case 'mail.uc.edu':
			case 'email.uc.edu':
			case 'email.unc.edu':
			case 'mail.co':
			case 'mail.umsl.edu':
			case 'email.sc.edu':
			case 'email.arizona.edu':
			case 'email.vccs.edu':
			case 'EMAIL.CHOP.EDU':
			case 'email.ws':
			case 'mail.com':
			case 'email.com':
			case 'email.us':
			case 'mail.ru':
			case 'mail.dc.state.fl.us':
			case 'mail-me.com':
			case 'mail.broward.edu':
			case 'mailout10.bedhash.com':
			case 'mail2go.com':
			case 'mailanthus.com':
			case 'mail-lb2-int.dca2.superb.net':
			case 'mail.ccsf.edu':
			case 'mail.usa.com':
			case 'mail.twu.edu':
			case 'mail.usmagazine.com':
			case 'mailbox.swipnet.se':
			case 'mail.lipscomb.edu':
			case 'mailsamolatina.com':
			case 'mail.fcboe.org':
			case 'mailcity.com':
			case 'mail.mil':
			case 'mail137.subscribermail.com':
			case 'mail23.dataentry-jobs.net':
			case 'mail2world.com':
			case 'mail2greece.com':
			case 'mail2think.com':
			case 'mail.wlu.edu':
			case 'mail.martinmethodist.edu':
			case 'mail.gvsu.edu':
			case 'mail.sio.midco.net':
			case 'mail2angela.com':
			case 'mailshipsolutions.com':
			case 'mail.cngold.org':
			case 'mail.lrm2.k12.wy.us':
			case 'mail2joseph.com':
			case 'mail.clxcpu.com':
			case 'mail.gatech.edu':
			case 'mail.kmutnb.ac.th':
			case 'mail.maehdros.be':
			case 'mail.networksolutionsemail':
			case 'mail.pioneeris.net':
			case 'mailpod.hostingplatform.co':
			case 'mailrelay4.gazprom.ru':
			case 'mail.plymouth.edu':
			case 'mail.mg-advertising.net':
			case 'mail.mn':
			case 'mail2maxwell.com':
			case 'mailer.com':
			case 'mail.missouri.edu':
			case 'mail2cool.com':
			case 'mail.uajy.ac.id':
			case 'mailcan.com':
			case 'mail333.com':
			case 'mailbag.com':
			case 'mail15.com':
			case 'mailtorch.com':
			case 'mailas.com':
			case 'mail.mvnu.edu':
			case 'mail.wccaheadstart.org':
			case 'mail.usi.edu':
			case 'mail.med.upenn.edu':
			case 'mailrelay2.gazprom.ru':
			case 'mail.tascom.ru':
			case 'mail.internetseer.com':
			case 'mail.hamiltontn.gov':
			case 'mail.okaloosa.k12.fl.us':
			case 'mailbox.sc.edu':
			case 'mail.clayton.edu':
			case 'mailcc.com':
			case 'mail.smu.edu':
			case 'mail.barry.edu':
			case 'mailhost.polarhome.com':
			case 'mailcui.com':
			case 'mail.roanoke.edu':
			case 'mail.bbahranice.cz':
			case 'mail-in5-pp.ewetel.de':
			case 'mailblc.org':
			case 'mail2art.com':
			case 'mail.kz':
			case 'mail.slh.wisc.edu':
			case 'mail.tpchel.ru':
			case 'mail.1stcml.com':
			case 'mail.kingsizedirect.com':
			case 'mail1.stofanet.dk':
			case 'mail.davenport.k12.ia.us':
			case 'mail.hotmail.com':
			case 'mail2michelle.com':
			case 'mail.auis.net':
			case 'mailbolt.con':
			case 'mail.bg':
			case 'mailbolt.com':
			case 'mail.bubblers.k12.pa.us':
			case 'mail.ftc.org':
			case 'mail4y.com':
			case 'mail.mhanet.com':
			case 'mail.bw.edu':
			case 'mail.net':
			case 'mail.manti.com':
			case 'mailinator.com':
			case 'mail.amc.edu':
			case 'mail.chpaman.edu':
			case 'mail.usf.edu':
			case 'mail.atlantisadventures.co':
			case 'maildiablo.com':
			case 'mail2web.com':
			case 'mail2usa.com':
			case 'mail2cowgirl.com':
			case 'mailsnare.net':
			case 'mail2cute.com':
			case 'mail2queen.com':
			case 'mailworksllc.com':
			case 'mail.cm':
			case 'mail2doris.com':
			case 'mail2christy.com':
			case 'mail.etsu.edu':
			case 'mail.montclair.edu':
			case 'mail.bradley.edu':
			case 'mailexcite.com':
			case 'mailbox.net':
			case 'mailpac.net':
			case 'mail.orbitel.bg':
			case 'mail.easynet.co.uk':
			case 'mail.telepac.pt':
			case 'mailattache.com':
			case 'mailonline.com':
			case 'mail.mylife.com':
			case 'mail.mst.edu':
			case 'mail.md':
			case 'mailmt.com':
			case 'mail.saturnfans.com':
			case 'mail.fr':
			case 'mail.stmarytx.edu':
			case 'mail.fresnostate.edu':
			case 'mailstation.com':
			case 'mail.cim':
			case 'mail.dccc.edu':
			case 'mail.om':
			case 'mail.wvu.edu':
			case 'mail.dumas-k12.net':
			case 'mail.chattanooga.gov':
			case 'mailer.fsu.edu':
			case 'mailtothis.com':
			case 'mail.k12.tn.us':
			case 'mail.cccd.edu':
			case 'mailboxprintandmail.com':
			case 'mail.uri.edu':
			case 'mail.oratoryschools.org':
			case 'mail.guam.net':
			case 'mail.xj-n-tax.gov.cn':
			case 'mail.ubc.ca':
			case 'mail.ci.lubbock.tx.us':
			case 'mail.roosevelt.edu':
			case 'mail.vresp.com':
			case 'mailtag.com':
			case 'mailpuppy.com':
			case 'mail.riverview.net':
			case 'mail2dancer.com':
			case 'mail.house.gov':
			case 'mail.afats.khc.edu.tw':
			case 'mail.ranbowintl.com':
			case 'mail.va':
			case 'mailde.de':
			case 'mailjmc.com':
			case 'mail.siom.ac.cn':
			case 'mail.widener.edu':
			case 'mailchimp.com':
			case 'mail.lced.net':
			case 'mail.goucher.edu':
			case 'maila.com':
			case 'mail2.dpa.de':
			case 'mailstop.com':
			case 'mailstop.co':
			case 'mailzeta.com':
			case 'mailandbusiness.com':
			case 'mailsitedirect.us':
			case 'mail.mizzou.edu':
			case 'mailtous.com':
			case 'mail.mccneb.edu':
			case 'mailstjohn.com':
			case 'mail.depaul.edu':
			case 'mailboxgenie.com':
			case 'mail.umkc.edu':
			case 'mail-nbf.kaydon.com':
			case 'mail.funtime-parts.de':
			case 'mail.glassdoctor.c':
			case 'mail.gtc.edu':
			case 'mailmalaga.com':
			case 'mail.raonoke.edu':
			case 'mail.csdnet.com.ar':
			case 'mailbox-3.com':
			case 'mailimate.com':
			case 'mail.fshu.edu':
			case 'mail.usciences.edu':
			case 'mail.sy':
			case 'mail.mpt.org':
			case 'mailhaven.com':
			case 'mail.shcnc.ac.cn':
			case 'mailtoko.com':
			case 'mail.shu.edu.cn':
			case 'mail.buffalostate.edu':
			case 'mail.mailinfinity.com':
			case 'mailinfinity.com':
			case 'mailturk.net':
			case 'mail.cfcc.edu':
			case 'mails.thu.edu.cn':
			case 'mail.mauricionassau.com.br':
			case 'mail.epc.k12.ar.us':
			case 'mail.hellosign.com':
			case 'mailpro1.com':
			case 'mail.kana.k12.wv.us':
			case 'mail54.fssprus.ru':
			case 'mailtmi.com':
			case 'mail.pf':
			case 'mailmix.pl':
			case 'maildrop.cc':
			case 'mail2.thejakartapost.com':
			case 'mailance.com':
			case 'mailcentrals.co.uk':
			case 'mail.xom':
			case 'mail4me.com':
			case 'mail2games.com':
			case 'mail.utoronto.ca':
			case 'mail.yzu.edu.tw':
			case 'mailsystem.au':
			case 'mail.unkc.edu':
			case 'mail-a.tvnetwork.hu':
			case 'mailfa.com':
			case 'mailismagic.com':
			case 'mail.nwmissouri.edu':
			case 'mail.c':
			case 'mail.lcc.edu':
			case 'mail.sunysuffolk.edu':
			case 'mail-efax.co.uk':
			case 'mail.cin':
			case 'mailbox.hu':
			case 'mail.blueonyx.it':
			case 'mailforspam.com':
			case 'maill.cim':
			case 'mailserver.i-next.psi.br':
			case 'maill.com':
			case 'mail.tt':
			case 'mail.fruitvaleisd.com':
			case 'mail.hz.zj.cn':
			case 'mail.winchart.com.tw':
			case 'mail.redzone.com.au':
			case 'mail.la-archdiocese.net':
			case 'mail.extravaganza-sweepstakes.co':
			case 'mail.xmission.com':
			case 'mailfw.whoisproxy.com':
			case 'mail.weber.edu':
			case 'mail.missouri.mail':
			case 'mail.lcs.net':
			case 'mail.ap-hm.fr':
			case 'mail.sogo.com.tw':
			case 'mail.c8m':
			case 'mail.cam':
			case 'mail.law.cuny.edu':
			case 'mail.hzic.edu.cn':
			case 'mail174.bizfree.kr':
			case 'mailbag.net':
			case 'mail.gmail.com':
			case 'mail2go.net':
			case 'mail.westco.net':
			case 'mail.endicott.edu':
			case 'mail.tmccentral.org':
			case 'mailoo.org':
			case 'mail.cmich.edu':
			case 'maildsi.com':
			case 'mail.austria.com':
			case 'mailplex.com':
			case 'mailtrace.cjb.net':
			case 'mail.sdsu.edu':
			case 'mail.sfsu.edu':
			case 'mailspot.org':
			case 'mail.cstudies.ubc.ca':
			case 'mails-gw.fnbs.net.my':
			case 'mail.ustc.edu.cn':
			case 'mail.wlc.edu':
			case 'mail.ms.maquoketa.k12.ia.us':
			case 'mail.scut.edu.cn':
			case 'mail.gardensnyc.net':
			case 'mailfrom.ru':
			case 'mail12.world4you.com':
			case 'mail.mira.dk':
			case 'mail.iccfa.com':
			case 'mail.sacredheart.edu':
			case 'mailbox99.net':
			case 'mail.darton.edu':
			case 'mail.ts-zipper.com.tw':
			case 'mail-exit-bc.narrowsui':
			case 'mail-bullet-hubs.sicens.net':
			case 'mailtcs.com':
			case 'mailrsi.com':
			case 'mailtpa.com':
			case 'mailnull.com':
			case 'mail.mcgill.ca':
			case 'mail.chapman.edu':
			case 'mailcatch.com':
			case 'mail.nih.gov':
			case 'mail.nplindia.ernet.in':
			case 'mail.oac.uncor.edu':
			case 'mail.nccu.edu':
			case 'mail2star.com':
			case 'mailserver.surfpacific.co':
			case 'mail.enet.com.':
			case 'mail.warp.co.nz':
			case 'mail.china.cn':
			case 'mail.rmu.edu':
			case 'mailaaa.com':
			case 'mail2king.com':
			case 'mail.worcester.k12.md.us':
			case 'mailprint.com':
			case 'mailpring.com':
			case 'mail.exis.it':
			case 'mail.aim-net.mx':
			case 'mail.nist.ro':
			case 'mail.rb.ru':
			case 'mail2ronald.com':
			case 'mail3.newulmtel.net':
			case 'mailboxz.net':
			case 'mail.primelsolutions.com':
			case 'mail9.ufmg.br':
			case 'mail.fhsu.edu':
			case 'mail.jkes.tp.edu.tw':
			case 'mail.boonty.com':
			case 'mail.casapellas.com.ni':
			case 'mail.ro':
			case 'mail.umw.edu':
			case 'mailumsl.edu':
			case 'mail.win.org':
			case 'mail.regent.edu':
			case 'mailfrhub.com':
			case 'mail.hocking.edu':
			case 'mail.plattsburgh.edu':
			case 'mail.techno-link.com':
			case 'mail.flyon.net':
			case 'mail.wvncc.edu':
			case 'mail2roy.com':
			case 'mailmeonline.net':
			case 'mailfern.com':
			case 'mail.techno-linc.com':
			case 'mail.ab.mec.edu':
			case 'usa.com':
			case 'usa.co':
			case 'usa.net':
			case 'usadig.com':
			case 'usa-spirit.com':
			case 'usabiz.biz':
			case 'usahealthvip.com':
			case 'usalaptoprepair.com':
			case 'usalendinginc.com':
			case 'usa2net.net':
			case 'usa-companies.net':
			case 'usagis-house.net':
			case 'usadma.com':
			case 'usamortgageinc.com':
			case 'usawide.net':
			case 'usamedia.tv':
			case 'usadutchinc.com':
			case 'usace.army.mil':
			case 'usa-labor.com':
			case 'usashs.com':
			case 'usadv.com':
			case 'usainnmotel.com':
			case 'usabg.net':
			case 'usasia-ins.com':
			case 'usabowman.com':
			case 'usana.com':
			case 'usanfsc.com':
			case 'usa.comif':
			case 'usadvantage.com':
			case 'usa-empire.com':
			case 'usastaffingnetwork.com':
			case 'usairlinepilots.org':
			case 'usableinterface.com':
			case 'usasend.com':
			case 'usa-networking.com':
			case 'usaprotection.com':
			case 'usa.comr':
			case 'usadrivers.com':
			case 'usahealth.edu':
			case 'usarmyvelo-1.com':
			case 'usabras.com':
			case 'usamedicus.com':
			case 'usalp.org':
			case 'usap.com':
			case 'usafricasynergy.org':
			case 'usadjustingservices.net':
			case 'usairways.com':
			case 'usa.varioline.com':
			case 'usaabs.com':
			case 'usainternationaldata.com':
			case 'usabioproducts.com':
			case 'usa2net.nert':
			case 'usalimoandsedan.com':
			case 'usahandcrafted.com':
			case 'usabel.com':
			case 'usagainstgreed.org':
			case 'usa.g4s.com':
			case 'usafa.edu':
			case 'usa.xerox.com':
			case 'usascheduler.com':
			case 'usaircraftsales.com':
			case 'usabilitycounts.com':
			case 'usatechsearch.com':
			case 'usa-travel-agency.com':
			case 'usafill.com':
			case 'usa-shade.com':
			case 'usa.cipom':
			case 'usaforever.net':
			case 'usadmail.com':
			case 'usatco.co.uk':
			case 'usa-security.net':
			case 'usa-11.com':
			case 'usace.amry.mil':
			case 'usaninc.com':
			case 'usaalarmsystems.com':
			case 'usa.apachecorp.com':
			case 'usa.ibs.org':
			case 'usaprivatesecurity.com':
			case 'usalendingandrealty.com':
			case 'usataxiandlimo.com':
			case 'usamusiic.org':
			case 'usafloortec.com':
			case 'usaddress.us':
			case 'usa.cm':
			case 'usarecyclingcenters.com':
			case 'usa-tsg.com':
			case 'usaswim.com':
			case 'usacracing.com':
			case 'usaaap.com':
			case 'usautomationcorp.com':
			case 'usaviator.net':
			case 'usaa.com':
			case 'usapromotionalcards.com':
			case 'usateamspirit.com':
			case 'usarmy.mil':
			case 'usavingsbank.com':
			case 'usakarateri.com':
			case 'usachoice.net':
			case 'usapathway.com':
			case 'usacoil.com':
			case 'usa-skating.com':
			case 'usapcpros.com':
			case 'usasset.com':
			case 'usafp.us':
			case 'usarmyjrotc.com':
			case 'usa.dupont.com':
			case 'usarice.com':
			case 'usacares.org':
			case 'usa829.org':
			case 'usamachinery.com':
			case 'usacann.com':
			case 'usadancedirectory.com':
			case 'usa-4u.com':
			case 'usavgroup.com':
			case 'usahockey.org':
			case 'usahostels.com':
			case 'usamobility.com':
			case 'usaelg.com':
			case 'usafleamarkets.com':
			case 'usatoday.com':
			case 'usamontana.com':
			case 'usavepharmacy.com':
			case 'usaautocenter.com':
			case 'usau.com':
			case 'usaautosales.net':
			case 'usadistillers.com':
			case 'usa.norgren.com':
			case 'usaverx.com':
			case 'usanet.net':
			case 'usajohn.com':
			case 'usa-american.net':
			case 'usaccessconsultants.com':
			case 'usafieldhockey.com':
			case 'usatrailers.com':
			case 'usarchitectsmuncie.com':
			case 'usaenduranceevents.com':
			case 'usa.striata.com':
			case 'usawarvet.org':
			case 'usaind.com':
			case 'usask.ca':
			case 'usatek.net':
			case 'usaflags.com':
			case 'usacomminc.com':
			case 'usalc.net':
			case 'usairportparking.com':
			case 'usao.edu':
			case 'usainc.org':
			case 'usaclean.com':
			case 'usachampion.com':
			case 'usagypsum.com':
			case 'usaverents.com':
			case 'usatankwash.com':
			case 'usa.redcross.org':
			case 'usairm.com':
			case 'usamotorhost.com':
			case 'usautomationcontrols.com':
			case 'mailers.co.uk':
			case 'mail-bip.com':
			case 'mail.ee':
			case 'mail.wowmail.com':
			case 'mail.dk':
			case 'mailoans.biz':
			case 'mail4millers.com':
			case 'mail.nu':
			case 'mail.sgigrain.com':
			case 'mail.tcitys.org':
			case 'mail.freelotto.com':
			case 'mail.valenciacollege.edu':
			case 'mailamerica.com':
			case 'mail2wonder.com':
			case 'mailc.net':
			case 'mailcbi.com':
			case 'mail.arizona.edu':
			case 'mailpsk.com':
			case 'mailaka.net':
			case 'mailliard.com':
			case 'mailbox.winthrop.edu':
			case 'mail.globalair.com':
			case 'mail.yu.edu':
			case 'mailme.dk':
			case 'mail2senegal.com':
			case 'mail.adsl4less.com':
			case 'mail.ri.net':
			case 'mailshack.com':
			case 'mail.csky.net':
			case 'maillie.com':
			case 'mailcruiser.keene.edu':
			case 'mail.uoguelph.ca':
			case 'mail.kearsley.k12.mi.us':
			case 'mail.rrdsb.com':
			case 'mail.utexas.edu':
			case 'mailbush.com':
			case 'mailmarketingfla.com':
			case 'mail.enr6.k12.mo.us':
			case 'mailmanagerinc.com':
			case 'mail.kvcc.edu':
			case 'mail.mhcdc.org':
			case 'mailbox2000.net':
			case 'mail.vu':
			case 'mailni.com':
			case 'mail.pt':
			case 'mailwagner.com':
			case 'mail.magee.edu':
			case 'mailpresort.com':
			case 'mail2expert.com':
			case 'mailprofiler.com':
			case 'mail.aimhigh.net':
			case 'mail.midsouthcc.edu':
			case 'mail.ie':
			case 'mail.uniontown.k12.pa.us':
			case 'mail2.lcia.com':
			{
				fputs($fileGmxUSA,$line);
			}
			break;
		  
		  
		  
		  case 'yahoo.com':
		  case 'ymail.com':
		  case 'rocketmail.com':
		  case 'yahoo.co.uk':
		  {
		    fputs($fileYahooUSA,$line);
		  }
		  break;
		  
		  
		  
		  
		  
		  
		  
		  
		 
	  }
	}
}


fclose($file);

//unlink('original/'.$data);

header('location:IU_List.php');
?>