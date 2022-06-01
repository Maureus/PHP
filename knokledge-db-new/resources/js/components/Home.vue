<template>
    <div class="text-2xl text-white">
        <div>Home page</div>
        <ul>
            <li>
                <div class="icon-container">
                    <div class="icon-rounded">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                <div class="text-container">
                    <div class="header"><h3>Lorem ipsum.</h3></div>
                    <div class="text">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus animi architecto, atque
                            aut blanditiis deleniti dolorem dolorum ex explicabo fuga illum iusto neque nobis quas
                            repellendus totam vitae voluptate voluptatum.</p>
                    </div>
                </div>
            </li>
            <li>
                <div class="icon-container">
                    <div class="icon-rounded">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                <div class="text-container">
                    <div class="header"><h3>Lorem ipsum.</h3></div>
                    <div class="text">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus animi architecto, atque
                            aut blanditiis deleniti dolorem dolorum ex explicabo fuga illum iusto neque nobis quas
                            repellendus totam vitae voluptate voluptatum.</p>
                    </div>
                </div>
            </li>
            <li>
                <div class="icon-container">
                    <div class="icon-rounded">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                <div class="text-container">
                    <div class="header"><h3>Lorem ipsum.</h3></div>
                    <div class="text">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus animi architecto, atque
                            aut blanditiis deleniti dolorem dolorum ex explicabo fuga illum iusto neque nobis quas
                            repellendus totam vitae voluptate voluptatum.</p>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "Home.vue"
}
</script>

<style scoped="scoped" lang="scss">
$indent : 0.25em;

.icon-rounded {
    background-color : #2d3748;
    border-radius    : 50%;
    text-align       : center;
    width            : 40px;
    height           : 40px;
    padding-top      : $indent * 1.2;
    float            : left;
}

.text-container {
    float            : left;
    width            : 93%;
    font-size        : 1.2em;
    background-color : white;
    color            : black;
    border-radius    : $indent * 2;
    margin           : $indent $indent * 2;

    .header {
        font-weight : bold;
        margin      : $indent * 4;
    }

    .text {
        margin : $indent * 4;
    }
}

li {
    margin : $indent * 6.5;
}
</style>
