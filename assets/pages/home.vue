<template>
    <div class="container-fluid bblack bg-warning">
        <div class="row">
            <aside class="col-xs-12 col-3">
                <div class="sidebar p-3 mb-5">
                    J'affiche une variable :<br>
                    firstname = {{ firstname }}.
                </div>
            </aside>
        </div>
    </div>
</template>
<style scoped>
.bblack {
    border: 2px solid black;
    box-shadow: 0px 0px 7px 4px #efefee;
    border-radius: 5px;
}
</style>
<script>
    export default {
        name: 'Home',
        data() {
            return {
                firstname: 'Fabien',
            };
        },
    };
</script>
// https://github.com/symfonycasts/vue/edit/master/sfcasts/ep1/setup.md