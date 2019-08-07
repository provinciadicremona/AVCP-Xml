<?php
/*
 * This file is part of project AVCP-Xml that can be found at:
 * https://github.com/provinciadicremona/AVCP-Xml
 * 
 * © 2013 Claudio Roncaglio <claudio.roncaglio@provincia.cremona.it>
 * © 2013 Gianni Bassini <gianni.bassini@provincia.cremona.it>
 * © 2013 Provincia di Cremona <sito@provincia.cremona.it>
 * 
 * SPDX-License-Identifier: GPL-3.0-only
*/
?>

        </article>
      </div> <!-- /container -->

      <div id="push"></div>
    </div><!-- /wrap -->
    <footer>
      <div class="container">
        <div class="row">
          <div class="span3">
            <img src="img/logoProvinciaCremona.png" alt="Logo Provincia di Cremona" title="Provincia di Cremona" />
          </div>
          <div class="span5">
              <br />
              <address>
              Provincia di Cremona - U.R.P. e Servizi Interni<br />
              <a href="mailto:sito@provincia.cremona.it">
                <i class="icon-envelope"></i>&nbsp;sito@provincia.cremona.it</a>
                </address>
          </div>
          <div class="span4 text-right">
            <img src="img/gplv3.png" alt="GPL V3 Logo">
            <p>Software rilasciato sotto <a href="?mask=page&amp;do=licenza">licenza GNU GPL 3</a></p>
          </div>
        </div>
      </div>
    </footer>
  <!-- Le javascript
  ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.js"></script>
<?php
if (null !== $customJsScript) {
    echo $callToJqueryUI . PHP_EOL;
    echo $customJsScript . PHP_EOL;
}
?>
</body>
</html>
