   <div id="push"></div>
  </div> <!-- wrapper_main -->

    <div id="footer">
      <div class="container">
        <div class='row'>
          <div class='col-sm-3'><h6><?php echo $config->siteName; ?></h6>
            <a href='<?php echo $config->instDir; ?>/empty1.php'>About Us</a><br />
            <a href='<?php echo $config->instDir; ?>/contact.php'><?php echo lang("men_contact"); ?></a><br />
            <a href='<?php echo $config->instDir; ?>/empty2.php'>Another Link</a>
          </div>
          <div class='col-sm-3 footerFaqs'><h6><a href='<?php echo $config->instDir; ?>/faqs.php'>FAQs</a></h6>
            <?php
              $fkeys = range(0, count($faqs)-1);
              shuffle($fkeys);
              for ($i=0;$i<2;$i++) {
                $fkey = $fkeys[$i];
                echo "<a href='" . $config->instDir . "/faqs.php?" . time() . "#faq$fkey'>" . $faqs[$fkey]["q"] . "</a>";
              }
            ?>
            <a href='<?php echo $config->instDir; ?>/faqs.php'>more ...</a>
          </div>
          <div class='col-sm-3'><h6>Information</h6>
            &copy; 2013<?php echo (date("Y") > 2013 ? " - " . date("Y") : ""); ?><br />
            <a href='http://codecanyon.net/user/themac/' onclick="window.open(this.href); return false;">brought to you by The Mac</a><br />
            <a href='http://codecanyon.net/item/simple-file-sharer/4562987' onclick="window.open(this.href); return false;">exclusive on Codecanyon</a>
          </div>
          <div class='col-sm-3'><h6>other envato Sites</h6>
            <a href='http://themeforest.net' onclick="window.open(this.href); return false;">Themeforest</a><br />
            <a href='http://videohive.net' onclick="window.open(this.href); return false;">Videohive</a><br />
            <a href='http://graphicriver.net' onclick="window.open(this.href); return false;">Graphicriver</a>
          </div>
        </div>
      </div>
    </div>

  <?php 
    deblang(); 

    $SFS->sendLastPHPError();
  ?>

  </body>
</html>
