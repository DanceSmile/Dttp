<?php

require __DIR__."/../../vendor/autoload.php";

use Laravel\Lumen\Application;

$app = new Application(
    realpath(__DIR__.'/../')
);

function build_response($request)
{
    return response()->json([
        'headers' => $request->header(),
        'query' => $request->query(),
        'json' => $request->json()->all(),
        'form_params' => $request->request->all(),
    ], $request->header('Z-Status', 200));
}
$app->router->get('/get', function () {
    return build_response(app('request'));
});
$app->router->post('/post', function () {
    return build_response(app('request'));
});
$app->router->put('/put', function () {
    return build_response(app('request'));
});
$app->router->patch('/patch', function () {
    return build_response(app('request'));
});
$app->router->delete('/delete', function () {
    return build_response(app('request'));
});
$app->router->get('/redirect', function () {
    return redirect('redirected');
});
$app->router->get('/redirected', function () {
    return "Redirected!";
});
$app->router->get('/simple-response', function () {
    return "A simple string response";
});
$app->router->get('/timeout', function () {
    sleep(2);
});


$app->router->post('/multi-part', function () {
    return response()->json([
        'body_content' => app('request')->only(['foo', 'baz']),
        'has_file' => app('request')->hasFile('test-file'),
        'file_content' => file_get_contents($_FILES['test-file']['tmp_name']),
        'headers' => app('request')->header(),
    ], 200);
});
$app->run();


