<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Teste iHold - API Documentation</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui.css" />
</head>

<body>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui-bundle.js" crossorigin></script>
    <script src="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui-standalone-preset.js" crossorigin></script>
    <script>
        const env = "<?php echo getenv('APP_ENV') ?: 'production' ?>";
        const url = env === 'production'
            ? "{{ asset('swagger/swagger.yaml', true) }}"
            : "{{ asset('swagger/swagger.yaml', false) }}";

        window.onload = () => {
            window.ui = SwaggerUIBundle({
                urls: [
                    {
                        url: url,
                        name: 'v1'
                    },
                ],
                dom_id: '#swagger-ui',
                defaultModelsExpandDepth: 0,
                validatorUrl: null,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset,
                ],
                layout: "StandaloneLayout",
                deepLinking: true,
            });
        };
    </script>
</body>

</html>
