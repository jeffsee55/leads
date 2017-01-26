(function( $ ) {
    'use strict';

    var searchOptions = {
        beforeSubmit: searchRequest,
        success:  searchResponse,
        error:    searchError,
        url:      VRAjax.ajaxurl,
        dataType: "json",
        data:     {
            search_nonce: VRAjax.search_nonce
        }
    }

    $('#q4vr_search_ajax').ajaxForm(searchOptions);

    function searchRequest(formData, jqForm, options) {

        $('#searchSubmit').html('Searching...').prop('disabled', true);

        $('#summary').hide();

        $('#results').addClass('show');

        $('#results').html( '<div style="text-align:center"><svg class="nc-icon glyph" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="48px" height="48px" viewBox="0 0 48 48"> <g> <circle class="nc-dots-5-2" data-color="color-2" fill="#939393" cx="24" cy="25" r="5" transform="translate(0 0)"></circle> <circle class="nc-dots-5-1" fill="#939393" cx="6" cy="25" r="5" transform="translate(0 0.1420499999076128)"></circle> <circle class="nc-dots-5-3" fill="#939393" cx="42" cy="25" r="5" transform="translate(0 0)"></circle> </g> <script>function dotsFiveStep(t){startDots5||(startDots5=t);var e=t-startDots5,n=Math.min(e/40,23);930&gt;e||(startDots5+=930);if(circleDots5[0][0]){window.requestAnimationFrame(dotsFiveStep);var a=[];for(j = 0; circleDots5Number &gt; j ; j++) {for(i=0;3&gt;i;i++){a[i]=Math.max(n-2*i,0);if(a[i]&gt;8)(a[i]=16-a[i]);a[i]=Math.max(a[i],0),circleDots5[i][j].setAttribute("transform","translate(0 "+a[i]+")")}}}}!function(){var t=0;window.requestAnimationFrame||(window.requestAnimationFrame=function(e){var i=(new Date).getTime(),n=Math.max(0,16-(i-t)),a=window.setTimeout(function(){e(i+n)},n);return t=i+n,a})}();var circleDots5=[],startDots5=null;circleDots5[0]=document.getElementsByClassName("nc-dots-5-1"),circleDots5[1]=document.getElementsByClassName("nc-dots-5-2"),circleDots5[2]=document.getElementsByClassName("nc-dots-5-3"),circleDots5Number = circleDots5[0].length,window.requestAnimationFrame(dotsFiveStep);</script> </svg></div>' );

        $('.more-information').css('margin-top', '8rem');
    }

    function searchResponse(responseText, statusText, xhr, $form)  {

        $('#searchSubmit').html('Search Again').prop('disabled', false);

        $('#results').hide().html(responseText.data).fadeIn();

        $('.more-information').css('margin-top', '18rem');
    }

    function searchError(request, textStatus, errorThrown) {

        $('#searchSubmit').html('Search Again').prop('disabled', false);

        $('#results').hide().html( '<div><h5 style="text-align: center">Failure</h5></div>' ).fadeIn()
    }

})( jQuery );
