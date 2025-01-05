<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Download de MP3</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <meta http-equiv="Content-Language" content="pt-br">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Estilos CSS para o slider */
        .va-carrousel-section {
            font-family: "Roboto";
            padding: 0px;
            position: relative;
        }

        .va-whitewrap {
            padding-top: 15px;
            margin-top: 15px;
        }

        .div-center {
            display: inline-block;
            width: 100%;
            text-align: center;
        }

        #va_container {
            position: relative;
            max-width: 1140px;
            margin: auto;
        }

        .va-thumbnail {
            border-radius: 5px;
            border: 0px;
            max-width: 100%;
        }

        .va-title {
            margin-top: 0px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .va-carrousel-flexbox {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .va-carrousel-flexbox .va-card {
            flex: 0 0 auto;
            padding-left: 15px;
        }

        .va-card {
            width: 100px;
            cursor: pointer;
            user-select: none;
            outline: none;
        }

        .va-card:hover {
            user-select: none;
            outline: none;
        }

        .va-card a {
            display: block;
        }

        .va-carrousel,
        .va-carrousel-flexbox {
            width: 100%;
            -webkit-overflow-scrolling: touch;
        }

        .va-carrousel::-webkit-scrollbar,
        .va-carrousel-flexbox::-webkit-scrollbar {
            display: none;
        }

        .deals-scroll-left,
        .deals-scroll-right {
            top: 0;
            bottom: 0;
            position: absolute;
            height: 38px;
            width: 38px;
            color: white;
            background-color: #00000089;
            border-radius: 50%;
            border: 0px solid;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .deals-scroll-left {
            left: 5px;
        }

        .deals-scroll-right {
            right: 5px;
        }

        /* Estilos adicionais */
        body {
            background: #232526;
            background: -webkit-linear-gradient(to right, #414345, #232526);
            background: linear-gradient(to right, #414345, #232526);
            color: white;
            font-family: Roboto, Arial, Helvetica, sans-serif;
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="va-carrousel-section">
        <div class="va-whitewrap">
            <div class="div-center">
                <div id="va_container">
                    <button class="deals-scroll-left deals-paddle">
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-left"
                            class="svg-inline--fa fa-chevron-left fa-w-10" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 320 512">
                            <path fill="currentColor"
                                d="M34.52 239.03L228.87 44.69c9.37-9.37 24.57-9.37 33.94 0l22.67 22.67c9.36 9.36 9.37 24.52.04 33.9L131.49 256l154.02 154.75c9.34 9.38 9.32 24.54-.04 33.9l-22.67 22.67c-9.37 9.37-24.57 9.37-33.94 0L34.52 272.97c-9.37-9.37-9.37-24.57 0-33.94z">
                            </path>
                        </svg>
                    </button>
                    
                    <div class="va-carrousel-flexbox">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="va-card">';
                                echo '<a href="#" class="link-plain" target="_blank">';
                                echo '<img class="va-thumbnail" src="' . $row["imagem"] . '">';
                                echo '<div class="va-title">' . $row["nome_musica"] . '</div>';
                                echo '<div class="va-start-from">' . $row["nome_dj"] . '</div>';
                                echo '</a>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>

                    <button class="deals-scroll-right deals-paddle">
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-right"
                            class="svg-inline--fa fa-chevron-right fa-w-10" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 320 512">
                            <path fill="currentColor"
                                d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.372-9.372-9.372-24.568 0-33.94L188.515 256 34.524 101.706c-9.372-9.372-9.372-24.568 0-33.94L57.19 45.099c9.373-9.373 24.569-9.373 33.941 0L285.476 239.03c9.373 9.372 9.373 24.568 0 33.941z">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para fazer o slider funcionar
        $(document).ready(function () {
            var slider = $(".va-carrousel-flexbox");

            $(".deals-scroll-right").click(function () {
                slider.animate({
                    scrollLeft: "+=200px"
                }, "slow");
            });

            $(".deals-scroll-left").click(function () {
                slider.animate({
                    scrollLeft: "-=200px"
                }, "slow");
            });
        });
    </script>
</body>
</html>
