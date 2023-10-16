<!--begin::Scrolltop-->
<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
    <i class="ki-duotone ki-arrow-up">
        <span class="path1"></span>
        <span class="path2"></span>
    </i>
</div>
<!--end::Scrolltop-->

<!--end::Modals-->
<!--begin::Javascript-->
<script>var hostUrl = "assets/";</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="<?php echo (isset($_SESSION['URL']) ? $_SESSION['URL']  : '' ); ?>/assets/plugins/global/plugins.bundle.js"></script>
<script src="<?php echo (isset($_SESSION['URL']) ? $_SESSION['URL']  : '' ); ?>/assets/js/scripts.bundle.js"></script>

<!--end::Global Javascript Bundle-->
<!--end::Javascript-->

<script>
const target = document.getElementById('kt_clipboard_2');
const button = target.nextElementSibling;

// Init clipboard -- for more info, please read the offical documentation: https://clipboardjs.com/
var clipboard = new ClipboardJS(button, {
    target: target,
    text: function() {
        return target.innerText;
    }
});

// Success action handler
clipboard.on('success', function(e) {
    const currentLabel = button.innerHTML;

    // Exit label update when already in progress
    if(button.innerHTML === 'Copiado!'){
        return;
    }

    // Update button label
    button.innerHTML = 'Copiado!';

    // Revert button label after 3 seconds
    setTimeout(function(){
        button.innerHTML = currentLabel;
    }, 3000)
});
</script>