<!DOCTYPE html>
<html>
	<head>
      <?php
        //
        //CHARSET
        //
        if($this->mainjson->seo->charset != null){
          ?>
            <meta charset = "<?php echo $this->mainjson->seo->charset; ?>">
          <?php
        }
        if($this->localjson->seo->charset != null){
          ?>
            <meta charset = "<?php echo $this->localjson->seo->charset; ?>">
          <?php
        }
        //
        //META
        //
        if ($this->mainjson->seo->meta != null) {
          foreach($this->mainjson->seo->meta as $meta => $data){
            ?>
            <meta name="<?php echo $meta; ?>" content = "<?php echo $data; ?>">
            <?php
          }
        }
        if ($this->localjson->seo->meta != null) {
          foreach($this->localjson->seo->meta as $meta => $data){
            ?>
            <meta name="<?php echo $meta; ?>" content = "<?php echo $data; ?>">
            <?php
          }
        }
        //
        //OG
        //
        if ($this->mainjson->seo->og != null) {
          foreach($this->mainjson->seo->og as $og => $data){
            ?>
            <meta name="<?php echo $og; ?>" content = "<?php echo $data; ?>">
            <?php
          }
        }
        if ($this->localjson->seo->og != null) {
          foreach($this->localjson->seo->og as $og => $data){
            ?>
            <meta name="<?php echo $og; ?>" content = "<?php echo $data; ?>">
            <?php
          }
        }
        //
        //CSS
        //
        if ($this->mainjson->css != null) {
          foreach($this->mainjson->css as $css){
            ?>
            <link type="text/css" rel="stylesheet" href = "<?php echo $css; ?>">
            <?php
          }
        }
        if ($this->localjson->css != null) {
          foreach($this->localjson->css as $css){
            ?>
            <link type="text/css" rel="stylesheet" href = "<?php echo $css; ?>">
            <?php
          }
        }
        //
        //FONTS
        //
        if ($this->mainjson->fonts != null) {
          foreach($this->mainjson->fonts as $fontdata){
            ?>
            <link rel="stylesheet" href = "<?php echo $fontdata; ?>">
            <?php
          }
        }
        if ($this->localjson->fonts != null) {
          foreach($this->localjson->fonts as $fontdata){
            ?>
            <link rel="stylesheet" href = "<?php echo $fontdata; ?>">
            <?php
          }
        }
        //
        //JS
        //
        if ($this->mainjson->js != null) {
          foreach($this->mainjson->js as $js){
            ?>
              <script type="text/javascript" src = "<?php echo $js; ?>"></script>
            <?php
          }
        }
        if ($this->localjson->js != null) {
          foreach($this->localjson->js as $js){
            ?>
              <script type="text/javascript" src = "<?php echo $js; ?>"></script>
            <?php
          }
        }

      ?>
      <title>
        <?php
          if($this->localjson->seo->title != null){
            echo $this->localjson->seo->title;
          }
          else if($this->mainjson->seo->title != null){
            echo $this->mainjson->seo->title;
          }
          else echo "Letsnet";
        ?>
      </title>
	</head>
	<body>
