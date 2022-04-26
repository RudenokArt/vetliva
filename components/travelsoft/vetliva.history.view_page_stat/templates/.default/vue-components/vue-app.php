<template id="vue-app-template">
    <div class="white-area">
        <div class="row">
            <div class="col-md-12">
                <conversion/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-12"><age/></div>
            <div class="col-md-6 col-sm-12 col-xs-12"><male/></div>
            <div class="col-md-6 col-sm-12 col-xs-12"><geography/></div>
            <div class="col-md-6 col-sm-12 col-xs-12"><devices/></div>
        </div>
    </div>
</template>

<script>
    BX.Vue.create({
        el: "#vue-app",
        template: document.getElementById('vue-app-template')
    });
</script>