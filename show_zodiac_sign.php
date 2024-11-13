<?php include('./layouts/header.php'); ?>

<div class="container mt-5 text-center">
    <h1>O seu signo é:</h1>

    <?php
    $data_nascimento = $_POST['data_nascimento'] ?? null;

    if ($data_nascimento) {
        $signos = simplexml_load_file("./signos.xml");
        if (!$signos) {
            echo "<p style='color: white;'>Erro ao carregar o arquivo de signos.</p>";
            exit;
        }

        try {
            $data_nascimento = new DateTime($data_nascimento);
        } catch (Exception $e) {
            echo "<p style='color: white;'>Data de nascimento inválida. Por favor, use o formato AAAA-MM-DD.</p>";
            exit;
        }

        function encontrarSigno($data_nascimento, $signos) {
            foreach ($signos->signo as $signo) {
                $data_inicio = DateTime::createFromFormat('d/m/Y', (string)$signo->dataInicio . '/' . $data_nascimento->format('Y'));
                $data_fim = DateTime::createFromFormat('d/m/Y', (string)$signo->dataFim . '/' . $data_nascimento->format('Y'));

                if ($data_inicio > $data_fim) {
                    $data_fim->modify('+1 year');
                }

                if ($data_nascimento >= $data_inicio && $data_nascimento <= $data_fim) {
                    return $signo;
                }

                if ($data_nascimento->format('d/m') == $data_inicio->format('d/m') || $data_nascimento->format('d/m') == $data_fim->format('d/m')) {
                    return $signo;
                }
            }
            return null;
        }

        $signo_encontrado = encontrarSigno($data_nascimento, $signos);

        if ($signo_encontrado) {
            echo "<h2 class='signo-r'>{$signo_encontrado->signoNome}</h2>";
            echo "<p style='color: #e3e1e3;'>{$signo_encontrado->descricao}</p>";
        } else {
            echo "<p style='color: #ffffff;'>Não foi possível determinar seu signo. Verifique a data informada ou o formato do XML.</p>";
        }
    } else {
        echo "<p style='color: #ffffff;'>Data de nascimento não fornecida.</p>";
    }
    ?>

    <a href="index.php" class="btn" style="color: white; border-radius: 5px; font-weight: bold; width: 100px;">Voltar</a>
</div>

<?php include('./layouts/footer.php'); ?>
