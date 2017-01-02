<?php
// Routes

/*$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});*/


$app->get('/','MainController:index')->setName('index');
$app->post("/usuario","MainController:usuario")->setName("usuario");
$app->post("/login","MainController:login")->setName("login");
$app->get("/main","MainController:main")->setName("main");
$app->get("/cifcliente/{cif}","MainController:cifcliente")->setName("cifcliente");
$app->get("/empresa/{id}","MainController:getempresaid")->setName("getempresaid");
$app->post("/hacerfactura","MainController:hacerfactura")->setName("hacerfactura");
$app->post("/validardatosfactura","MainController:validardatosfactura")->setName("validardatosfactura");
$app->get("/factura/{num}/{idemp}","MainController:getfacturanum")->setName("getfacturanum");
$app->get("/haceralbaran","MainController:haceralbaran")->setName("haceralbaran");
$app->post("/validardatosalbaran","MainController:validardatosalbaran")->setName("validardatosalbaran");
$app->post("/grabaralbaran","MainController:grabaralbaran")->setName("grabaralbaran");
$app->get("/albaran/{num}/{clicif}","MainController:getalbanum")->setName("getalbanum");
$app->get("/veralbaranes/{clicif}","MainController:getalbaranescif")->setName("getalbaranescif");
$app->post("/cambiologo","MainController:cambiologo")->setName("cambiologo");
$app->get("/clientes/{id}","MainController:clientes")->setName("clientes");
$app->put("/anadircliente","MainController:anadircliente")->setName("anadircliente");
$app->get("/empresas","MainController:empresas")->setName("empresas");
$app->put("/anadirempresa","MainController:anadirempresa")->setName("anadirempresa");
