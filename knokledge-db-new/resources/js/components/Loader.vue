<template>
    <div class="loader-div">
        <div class="lds-ring">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Loader"
}
</script>

<style scoped="scoped" lang="scss">

.loader-div {
    width      : 100%;
    text-align : center;
}

.lds-ring {
    display  : inline-block;
    position : relative;
    width    : 180px;
    height   : 180px;

    div {
        box-sizing    : border-box;
        display       : block;
        position      : absolute;
        width         : 94px;
        height        : 94px;
        margin        : 8px;
        border        : 8px solid #fcf;
        border-radius : 50%;
        animation     : lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        border-color  : #fcf transparent transparent transparent;

        &:nth-child(1) {
            animation-delay : -0.45s;
        }

        &:nth-child(2) {
            animation-delay : -0.3s;
        }

        &:nth-child(3) {
            animation-delay : -0.15s;
        }
    }
}

@keyframes lds-ring {
    0% {
        transform : rotate(0deg);
    }
    100% {
        transform : rotate(360deg);
    }
}
</style>
