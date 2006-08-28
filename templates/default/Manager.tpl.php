<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" ><!-- InstanceBegin template="/Templates/php.fixed.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $this->doctitle; ?></title>
<!-- InstanceEndEditable -->
<link rel="stylesheet" type="text/css" media="screen" href="/ucomm/templatedependents/templatecss/layouts/main.css" />
<link rel="stylesheet" type="text/css" media="print" href="/ucomm/templatedependents/templatecss/layouts/print.css"/>
<script type="text/javascript" src="/ucomm/templatedependents/templatesharedcode/scripts/all_compressed.js"></script>

<?php require_once($GLOBALS['unl_template_dependents'].'/templatesharedcode/includes/browsersniffers/ie.html'); ?>
<?php require_once($GLOBALS['unl_template_dependents'].'/templatesharedcode/includes/comments/developersnote.html'); ?>
<?php require_once($GLOBALS['unl_template_dependents'].'/templatesharedcode/includes/metanfavico/metanfavico.html'); ?>
<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" type="text/css" media="screen" href="templates/@TEMPLATE@/manager_main.css" />
<script type="text/javascript">
function showHide(e)
{
   document.getElementById(e).style.display=(document.getElementById(e).style.display=="block")?"none":"block";
   return false;
}

function checknegate(id){
	checkevent(id);
}

function highlightLine(l,id) {
	animation(l,id);	
	checkevent(id);
}

function animation(l,id){
	var TRrow = "row" + id;
	if(!l.className){
	Spry.Effect.Highlight(TRrow,{duration:400,from:'#ffffff',to:'#ffffcc',restoreColor:'#ffffcc',toggle: true});
	}
	else{
	Spry.Effect.Highlight(TRrow,{duration:400,from:'#e8f5fa',to:'#ffffcc',restoreColor:'#ffffcc',toggle: true});
	} 
}

function checkevent(id) {
	 checkSet = eval("document.formlist.event" + id);
	 checkSet.checked = !checkSet.checked
}

</script>
<!-- InstanceEndEditable -->
</head>
<body <?php echo $this->uniquebody; ?>>
<!-- InstanceBeginEditable name="siteheader" -->
<?php require_once($GLOBALS['unl_template_dependents'].'/templatesharedcode/includes/siteheader/siteheader.shtml'); ?>
<!-- InstanceEndEditable -->
<div id="red-header">
	<div class="clear">
		<h1>University of Nebraska&ndash;Lincoln</h1>
		<div id="breadcrumbs"> <!-- InstanceBeginEditable name="breadcrumbs" -->
			<!-- WDN: see glossary item 'breadcrumbs' -->
			<ul>
				<li class="first"><a href="http://www.unl.edu/">UNL</a></li>
				<li>Events</li>
			</ul>
			<!-- InstanceEndEditable --> </div>
	</div>
</div>
<!-- close red-header -->
  
<?php require_once($GLOBALS['unl_template_dependents'].'/templatesharedcode/includes/shelf/shelf.shtml'); ?>

<div id="container">
	<div class="clear">
		<div id="title"> <!-- InstanceBeginEditable name="collegenavigationlist" --> <!-- InstanceEndEditable -->
			<div id="titlegraphic">
				<!-- WDN: see glossary item 'title graphics' -->
				<!-- InstanceBeginEditable name="titlegraphic" -->
				<h1>UNL's Event Publishing System</h1>
				<h2>Plan. Publish. Share.</h2>
				<!-- InstanceEndEditable --></div>
			<!-- maintitle -->
		</div>
		<!-- close title -->
		
		<div id="navigation">
			<h4 id="sec_nav">Navigation</h4>
			<!-- InstanceBeginEditable name="navcontent" -->
			<div id="navlinks">
				<?php echo $this->navigation; ?>
			</div>
			<!-- InstanceEndEditable -->
			<div id="nav_end"></div>
			<!-- InstanceBeginEditable name="leftRandomPromo" -->
			<!-- InstanceEndEditable -->
			<!-- WDN: see glossary item 'sidebar links' -->
			<div id="leftcollinks"> <!-- InstanceBeginEditable name="leftcollinks" -->
				<h3>Related Links</h3>
				<ul>
					<li><a href="<?php echo $this->frontenduri; ?>">Events</a></li>
				</ul>
				<!-- InstanceEndEditable --> </div>
		</div>
		<!-- close navigation -->
		
		<div id="main_right" class="mainwrapper">
			<!--THIS IS THE MAIN CONTENT AREA; WDN: see glossary item 'main content area' -->
			
			<div id="maincontent"> <!-- InstanceBeginEditable name="maincontent" -->
				<div class="two_col left"><?php UNL_UCBCN::displayRegion($this->output); ?></div>
				<div class="col right">
					<?php UNL_UCBCN::displayRegion($this->accountright); ?>
				</div>
				<!-- InstanceEndEditable --> </div>
			 </div>
		<!-- close main right -->
	</div>
</div>
<!-- close container -->

<div id="footer">
	<div id="footer_floater"> <!-- InstanceBeginEditable name="optionalfooter" --> <!-- InstanceEndEditable -->
		<div id="copyright"> <!-- InstanceBeginEditable name="footercontent" -->
			
			<!-- InstanceEndEditable --> <span><a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> <a href="http://validator.w3.org/check/referer">W3C</a> <a href="http://www-1.unl.edu/feeds/">RSS</a> </span><a href="http://www.unl.edu/" title="UNL Home"><img src="/ucomm/templatedependents/templatecss/images/wordmark.png" alt="UNL's wordmark" id="wordmark" /></a></div>
	</div>
</div>

<!-- close footer -->
<!-- sifr -->
<script type="text/javascript" src="/ucomm/templatedependents/templatesharedcode/scripts/sifr_replacements.js"></script>
</body>
<!-- InstanceEnd --></html>
