// import './jquery.lightbox_me.min'; // Uncomment to include jquery.lightbox_me.min.js file.
(function($){

    // Helps equalizing height of elements
    function equalizeHeights(elements){
      elements.forEach(function(elem){
          var $element = $(elem[0]);
          if($element.length > 0){
            var maxHeight = 0;
            if(elem[1] === false){
              if($(window).width() > 767){
                $element.each(function() {
                  if ($(this).outerHeight() > maxHeight) {
                      maxHeight = $(this).outerHeight();
                  }
                }).height(maxHeight);
              }
            }
            else{
              $element.each(function() {
                if ($(this).outerHeight() > maxHeight) {
                    maxHeight = $(this).outerHeight();
                }
              }).height(maxHeight);
            }
          }
      });
    }

    // Run code when docuemnt is ready
    $(document).ready(function(){

      // Equalize height of the following elements
      var equalizeHeightElements = [
          ['.wp-block-query .footer-blog .wp-block-post-title', false],
          ['.wp-block-query .footer-blog li .wp-block-post-excerpt__excerpt', false],
          ['.ast-article-post .post-content .entry-header .entry-title', false],
          ['.ast-article-post .post-content .entry-content p:first-child', false],
          ['.two-column-row .inner-block .wp-block-cover__inner-container h3', false],
          
      ];
      equalizeHeights(equalizeHeightElements);

      // Equalize height of elements on page resize
      $(window).resize(function(){
        equalizeHeights(equalizeHeightElements);
      });

      // Equalize height of elements on Search and Filter AJAX completion
      $(document).on('sf:ajaxfinish', '.searchandfilter', function(){
        equalizeHeights(equalizeHeightElements);
      });

      // Your JS code here.

      $('select').on('change',function() {
        window.location = $(this).val();
      });

      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
          anchor.addEventListener('click', function (e) {
              e.preventDefault();

              document.querySelector(this.getAttribute('href')).scrollIntoView({
                  behavior: 'smooth',
              });
          });
      });

    });

})(jQuery);
