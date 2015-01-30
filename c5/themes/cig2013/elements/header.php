<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<!DOCTYPE html>
<html lang="<?php echo LANGUAGE?>">

<!-- JQuery added 5/15/2014 (we should really already have had this.) -->
<script type="text/javascript" stc="//code.jquery.com/jquery-1.11.0.min.js"></script>
<!-- MathJax Plugin added 3/13/2014 -->
<script type="text/javascript" src="//cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>


<head>

<?php Loader::element('header_required'); ?>

<link rel="stylesheet" media="screen" type="text/css" href="<?php  echo $this->getStyleSheet('/css/main.css')?>" />
<link rel="stylesheet" media="screen" type="text/css" href="<?php  echo $this->getStyleSheet('/css/nav.css')?>" />
<link rel="stylesheet" media="screen" type="text/css" href="<?php  echo $this->getStyleSheet('/css/form.css')?>" />

<!-- for software page -->
   <style type="text/css">
      body { display: block; }
      #list, #list1, #list2 {display:none; }
      .hide, .show { color: #002855;
        font-weight: bold;
        text-decoration: none;
	}
      .hide:hover, .show:hover { color: #77160B;
        font-weight: bold;
        text-decoration: none;
	cursor: pointer; cursor: hand;
	}
      @media print { .hide, .show { display: none; } }
   </style>



</head>

<body>

<div id="main-container" class="container_24 second_page">



<script type="text/javascript">
<!--
    function hide(id) {
       var e = document.getElementById(id);
/*
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
*/
          e.style.display = 'none';
    }

    function show(id) {
       var e = document.getElementById(id);
/*
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
*/
          e.style.display = 'block';
    }
//-->
</script>




<!-- Google search added by Eric Heien, 3/5/2014 -->
<div class=search id='cse' style='width: 20%;'>Loading</div>
<script src='//www.google.com/jsapi' type='text/javascript'></script>
<script type='text/javascript'>
google.load('search', '1', {language: 'en', style: google.loader.themes.V2_DEFAULT});
google.setOnLoadCallback(function() {
  var customSearchOptions = {};
  var orderByOptions = {};
  orderByOptions['keys'] = [{label: 'Relevance', key: ''} , {label: 'Date', key: 'date'}];
  customSearchOptions['enableOrderBy'] = true;
  customSearchOptions['orderByOptions'] = orderByOptions;
  customSearchOptions['overlayResults'] = true;
  var customSearchControl =   new google.search.CustomSearchControl('008203384720007190552:vky80oi4vje', customSearchOptions);
  customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
  var options = new google.search.DrawOptions();
  options.setAutoComplete(true);
  customSearchControl.draw('cse', options);
}, true);
</script>
<style type='text/css'>
  .gsc-control-cse {
    font-family: Arial, sans-serif;
    border-color: #002666;
    background-color: #002666;
  }
  .gsc-control-cse .gsc-table-result {
    font-family: Arial, sans-serif;
  }
  input.gsc-input, .gsc-input-box, .gsc-input-box-hover, .gsc-input-box-focus {
    border-color: #D9D9D9;
  }
  input.gsc-search-button, input.gsc-search-button:hover, input.gsc-search-button:focus {
    border-color: #666666;
    background-color: #CECECE;
    background-image: none;
    filter: none;

  }
  .gsc-tabHeader.gsc-tabhInactive {
    border-color: #FF9900;
    background-color: #FFFFFF;
  }
  .gsc-tabHeader.gsc-tabhActive {
    border-color: #E9E9E9;
    background-color: #E9E9E9;
    border-bottom-color: #FF9900
  }
  .gsc-tabsArea {
    border-color: #FF9900;
  }
  .gsc-webResult.gsc-result, .gsc-results .gsc-imageResult {
    border-color: #FFFFFF;
    background-color: #FFFFFF;
  }
  .gsc-webResult.gsc-result:hover, .gsc-imageResult:hover {
    border-color: #FFFFFF;
    background-color: #FFFFFF;
  }
  .gs-webResult.gs-result a.gs-title:link, .gs-webResult.gs-result a.gs-title:link b, .gs-imageResult a.gs-title:link, .gs-imageResult a.gs-title:link b  {
    color: #0000CC;
  }
  .gs-webResult.gs-result a.gs-title:visited, .gs-webResult.gs-result a.gs-title:visited b, .gs-imageResult a.gs-title:visited, .gs-imageResult a.gs-title:visited b {
    color: #0000CC;
  }
  .gs-webResult.gs-result a.gs-title:hover, .gs-webResult.gs-result a.gs-title:hover b, .gs-imageResult a.gs-title:hover, .gs-imageResult a.gs-title:hover b {
    color: #0000CC;
  }
  .gs-webResult.gs-result a.gs-title:active, .gs-webResult.gs-result a.gs-title:active b, .gs-imageResult a.gs-title:active, .gs-imageResult a.gs-title:active b {
    color: #0000CC;
  }
  .gsc-cursor-page {
    color: #0000CC;
  }
  a.gsc-trailing-more-results:link {
    color: #0000CC;
  }
  .gs-webResult .gs-snippet, .gs-imageResult .gs-snippet, .gs-fileFormatType {
    color: #000000;
  }
  .gs-webResult div.gs-visibleUrl, .gs-imageResult div.gs-visibleUrl {
    color: #008000;
  }
  .gs-webResult div.gs-visibleUrl-short {
    color: #008000;
  }
  .gs-webResult div.gs-visibleUrl-short  {
    display: none;
  }
  .gs-webResult div.gs-visibleUrl-long {
    display: block;
  }
  .gs-promotion div.gs-visibleUrl-short {
    display: none;
  }
  .gs-promotion div.gs-visibleUrl-long  {
    display: block;
  }
  .gsc-cursor-box {
    border-color: #FFFFFF;
  }
  .gsc-results .gsc-cursor-box .gsc-cursor-page {
    border-color: #E9E9E9;
    background-color: #FFFFFF;
    color: #0000CC;
  }
  .gsc-results .gsc-cursor-box .gsc-cursor-current-page {
    border-color: #FF9900;
    background-color: #FFFFFF;
    color: #0000CC;
  }
  .gsc-webResult.gsc-result.gsc-promotion {
    border-color: #336699;
    background-color: #FFFFFF;
  }
  .gsc-completion-title {
    color: #0000CC;
  }
  .gsc-completion-snippet {
    color: #000000;
  }
  .gs-promotion a.gs-title:link,.gs-promotion a.gs-title:link *,.gs-promotion .gs-snippet a:link  {
    color: #0000CC;
  }
  .gs-promotion a.gs-title:visited,.gs-promotion a.gs-title:visited *,.gs-promotion .gs-snippet a:visited {
    color: #0000CC;
  }
  .gs-promotion a.gs-title:hover,.gs-promotion a.gs-title:hover *,.gs-promotion .gs-snippet a:hover  {
    color: #0000CC;
  }
  .gs-promotion a.gs-title:active,.gs-promotion a.gs-title:active *,.gs-promotion .gs-snippet a:active {
    color: #0000CC;
  }
  .gs-promotion .gs-snippet, .gs-promotion .gs-title .gs-promotion-title-right, .gs-promotion .gs-title .gs-promotion-title-right * {
    color: #000000;
  }
  .gs-promotion .gs-visibleUrl,.gs-promotion .gs-visibleUrl-short  {
    color: #008000;
  }
</style>
</DIV>

<!--
<DIV class=search>

  <FORM class=search id="searchbox_018149662233696839720:o61ai7f-hp8" action="/cig/search/">
    <INPUT name=query> <BUTTON type=submit name="submit" value="Search">Search</BUTTON></FORM>

</DIV>
-->
<!-- end login -->

<!-- php below used to be in div -->
<?php /*
  $u = new User();
  if ($u->isRegistered()) { 
    $userName = $u->getUserName(); ?>

	<span class="sign_in">Signed In: <B><?php echo $userName ?></B>
	|
	<A HREF="<?php echo $this->url('/login', 'logout')?>">Sign Out</A>
	</span>
  <? } else { ?>
	<span class="sign_in"><A HREF="<?php echo $this->url('/login')?>">Sign In</A></span>
<?  }    */ ?>


<DIV class=header>
  <?php
  $a = new GlobalArea('Site Name');
  $a->setBlockLimit(1);
  $a->display($c);
  ?>
</DIV>

<DIV class=nav_main>
  <?php
  $a = new GlobalArea('Header Nav');
  $a->setBlockLimit(2);
  $a->display($c);
  ?>
</DIV>


<DIV class=full_content_area>
