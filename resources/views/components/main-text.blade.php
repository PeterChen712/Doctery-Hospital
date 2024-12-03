<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .card {
            --bg-color: rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            border-radius: 1.25rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .loader {
            color: rgb(124, 124, 124);
            font-family: "Poppins", sans-serif;
            font-weight: 500;
            font-size: 25px;
            -webkit-box-sizing: content-box;
            box-sizing: content-box;
            height: 40px;
            padding: 10px 10px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            border-radius: 8px;
        }

        .words {
            overflow: hidden;
            position: relative;
        }

        .words::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(var(--bg-color) 10%,
                    transparent 30%,
                    transparent 70%,
                    var(--bg-color) 90%);
            z-index: 20;
        }

        .word {
            display: block;
            height: 100%;
            padding-left: 6px;
            animation: spin_4991 4s infinite;
        }

        .word-1 {
            color: #956afa;
        }

        .word-2 {
            color: #ff6b6b;
        }

        .word-3 {
            color: #4ecdc4;
        }

        .word-4 {
            color: #ffbe0b;
        }

        .word-5 {
            color: #956afa;
        }

        @keyframes spin_4991 {
            10% {
                -webkit-transform: translateY(-102%);
                transform: translateY(-102%);
            }

            25% {
                -webkit-transform: translateY(-100%);
                transform: translateY(-100%);
            }

            35% {
                -webkit-transform: translateY(-202%);
                transform: translateY(-202%);
            }

            50% {
                -webkit-transform: translateY(-200%);
                transform: translateY(-200%);
            }

            60% {
                -webkit-transform: translateY(-302%);
                transform: translateY(-302%);
            }

            75% {
                -webkit-transform: translateY(-300%);
                transform: translateY(-300%);
            }

            85% {
                -webkit-transform: translateY(-402%);
                transform: translateY(-402%);
            }

            100% {
                -webkit-transform: translateY(-400%);
                transform: translateY(-400%);
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="loader">
            <p style="position: relative; top: 10px; margin-right: 10px;">Kami ada untuk membantu</p>
            <div class="words">
                <span class="word word-1">Anda</span>
                <span class="word word-2">Keluarga Anda</span>
                <span class="word word-3">Masyarakat</span>
                <span class="word word-4">Anak-Anak</span>
                <span class="word word-5">Anda</span>
            </div>
        </div>
    </div>
</body>

</html>