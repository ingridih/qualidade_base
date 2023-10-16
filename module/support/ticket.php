<?php 
    require_once $_SERVER['DOCUMENT_ROOT']."/menu/session.php";
    require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
    $Conexao = ConexaoMYSQL_ticket::getConnection();
    date_default_timezone_set('America/Sao_Paulo');

    
    $titulo = null;
    $criadopor = null;
    $mensagem = null;
    $data = null;
    $criadoporid = null;
    $urgente = null;
    $status = null;
    $descricao = null;

    $array_ticket = array();

    $status_ar = array(
        'A' => 'Aguardando Resposta',
        'Acolor' => 'primary',
        'R' => 'Respondido',
        'Rcolor' => 'success',
        'F' => 'Finalizado',
        'Fcolor' => 'secondary',
        'C' => 'Cancelado',
    );


    $query = $Conexao->query("SELECT T_ID, T_RESPOSTA, T_TIPO, T_URGENCIA, T_ASSUNTO, T_DESCRICAO, T_USUARIO, 
        DATE_FORMAT(T_DATA, '%d/%m/%Y %H:%i') T_DATA2, T_RESPOND_USU, T_STATUS, T_FAVORITO, T_EMAIL, T_SOLICITANTE AS CRIADOPOR, T_RESPONDEU AS RESPONDIDOPOR, T_DATA, T_URL
        FROM TICKET 
        WHERE T_ID = '".base64_decode($_GET['id'])."' OR T_RESPOSTA = '".base64_decode($_GET['id'])."' 
        ORDER BY T_DATA ASC");
    while ($row = $query->fetch()) {
        
        if($row['T_ID'] == base64_decode($_GET['id'])){ 
            $titulo = $row['T_ASSUNTO'];
            $criadopor = $row['CRIADOPOR'];
            $criadoporid = $row['T_USUARIO'];
            $urgente = $row['T_URGENCIA'];
            $status = $row['T_STATUS'];
            $descricao = $row['T_DESCRICAO'];

            $dataChamado = new DateTime($row['T_DATA']);
            $dataAtual = new DateTime();

            // Calculando a diferença entre as datas
            $dataAtual = new DateTime();
            // Calcula a diferença
            $diferenca = $dataAtual->diff($dataChamado);

            // Montando a mensagem de diferença
            $mensagem = "Criado há ";
            if ($diferenca->days > 0) {
                $mensagem .= $diferenca->days . " dia" . ($diferenca->days > 1 ? "s" : "");
                if ($diferenca->h > 0) {
                    $mensagem .= " e " . $diferenca->h . " hora" . ($diferenca->h > 1 ? "s" : "");
                }
            } elseif ($diferenca->h > 0) {
                $mensagem .= $diferenca->h . " hora" . ($diferenca->h > 1 ? "s" : "");
            }
            $mensagem .= " atrás";
            $data = $row['T_DATA2'];
        }else{
            $array_ticket[] = $row;
        }
        
    }

?>
<!DOCTYPE html>
<!--
oooooooo.....ooo...ooo...ooo
ooo...ooo....ooo...ooo...ooo
ooo...oooo...ooo...ooooooooo
ooo...ooo....ooo...ooo...ooo
ooooooo......ooo...ooo...ooo
-->
<html lang="pt-br">
	<!--begin::Head-->
	<head>
        <?php require $_SERVER['DOCUMENT_ROOT']."/menu/meta.php"; ?>
		<?php require $_SERVER['DOCUMENT_ROOT']."/menu/header.php"; ?>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<?php
		// Verifica se o cookie "sidebar_minimize_state" está definido
		if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") {
		// Se o cookie estiver definido como "on", define o atributo como "on"
		$sidebarMinimizeState = "on";
		} else {
		// Caso contrário, define o atributo como "off" ou qualquer outro valor padrão desejado
		$sidebarMinimizeState = "off";
	}
	?>

	<body id="kt_app_body" data-kt-app-sidebar-minimize="<?php echo $sidebarMinimizeState; ?>" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<!--begin::Header-->
				<!--=------------------------------------------------- START TOPBAR------------------------------------------------------------------------->
					<?php require $_SERVER['DOCUMENT_ROOT']."/menu/topbar.php"; ?>
				<!--=------------------------------------------------- END TOPBAR------------------------------------------------------------------------->
				<!--end::Header-->
				<!--begin::Wrapper-->
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<!--=------------------------------------------------- START MENU LATERAL------------------------------------------------------------------------->
					<?php require $_SERVER['DOCUMENT_ROOT']."/menu/menu.php"; ?>
					<!--begin::Main-->
					<!--=------------------------------------------------- END MENU LATERAL------------------------------------------------------------------------->
					<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<!--begin::Content wrapper-->
						<div class="d-flex flex-column flex-column-fluid">
							<!--begin::Content-->
							<div id="kt_app_content" class="app-content flex-column-fluid">
								<!--begin::Content container-->
								<div id="kt_app_content_container" class="app-container container-fluid">
                                <!----------------------------------------------------INSERIR DENTRO DO CONTENT---------------------------------------------------------------------------->
                                    <div class="mb-5 mb-xl-10">
                                        <div class="">
                                            <div class="d-flex flex-column flex-lg-row">
                                                <input type="hidden" id="idticket" value="<?php echo base64_decode($_GET['id']); ?>"/> 
                                                <!--begin::Content-->
                                                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                                                    <!--begin::Card-->
                                                    <div class="card">
                                                <!--begin::Card body-->
                                                <div class="card-body">
                                                    <h3 class="align-items-start flex-column" style="margin-left: 15px;">
                                                        <span class="card-label fw-bold fs-3 mb-1">Ticket <?php echo base64_decode($_GET['id']) ?></span> <span style="margin-left:30px" class="badge badge-lg badge-<?php echo $status_ar[$status.'color'] ?>"><?php echo $status_ar[$status] ?></span>
                                                        <span style="float:right"><?php echo ($urgente == 1) ? '<span class="badge badge-lg badge-light-danger">URGENTE</span>' : '<span class="badge badge-lg badge-light-primary">NORMAL</span>'  ?></span>
                                                        
                                                    </h3>
                                                    <!--begin::Layout-->
                                                    <div class="d-flex flex-column flex-xl-row p-7">
                                                        <!--begin::Content-->
                                                        <div class="flex-lg-row-fluid me-xl-15 mb-20 mb-xl-0">
                                                            <!--begin::Ticket view-->
                                                            <div class="mb-0">
                                                                <!--begin::Heading-->
                                                                <div class="d-flex align-items-center mb-12">
                                                                    <!--begin::Icon-->
                                                                    <i class="ki-duotone ki-file-added fs-4qx text-success ms-n2 me-3">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                    </i>
                                                                    <!--end::Icon-->
                                                                    <!--begin::Content-->
                                                                    <div class="d-flex flex-column">
                                                                        <!--begin::Title-->
                                                                        <h1 class="text-gray-800 fw-semibold"><?php echo $titulo ?></h1>
                                                                        
                                                                        <!--end::Title-->
                                                                        <!--begin::Info-->
                                                                        <div class="">
                                                                            <!--begin::Label-->
                                                                            <span class="fw-semibold text-muted me-6">Criado por:
                                                                            <a href="#" class="text-muted text-hover-primary"><?php echo $criadopor ?></a></span>
                                                                            <!--end::Label-->
                                                                            <!--begin::Label-->
                                                                            <span class="fw-semibold text-muted"><?php echo $mensagem; ?>
                                                                            <span class="fw-bold text-gray-600 me-1">(<?php echo $data; ?>)
                                                                            <!--end::Label-->
                                                                        </div>
                                                                        <!--end::Info-->
                                                                    </div>
                                                                    <!--end::Content-->
                                                                </div>
                                                                <!--end::Heading-->
                                                                <!--begin::Details-->
                                                                <div class="mb-15">
                                                                    <!--begin::Description-->
                                                                    <div class="mb-15 fs-5 fw-normal text-gray-800">
                                                                        <!--begin::Text-->
                                                                        <div class="mb-10"><?php echo $descricao ?></div>
                                                                    </div>

                                                                    <h6>Anexos</h6>
                                                                    <div class="d-flex flex-row mb-8">

                                                                    <?php  
                                                                    $existe = 0;
                                                                    $query2 = $Conexao->query("SELECT TA_ANEXO
                                                                        FROM TICKET_ANEXO 
                                                                        WHERE TA_TID = '".base64_decode($_GET['id'])."'");
                                                                    while ($row_arq = $query2->fetch()) {
                                                                        $extensao = pathinfo($row_arq['TA_ANEXO'], PATHINFO_EXTENSION);
                                                                        if($extensao == 'pdf' || $extensao == 'css' || $extensao == 'doc' || $extensao == 'xml' || $extensao == 'jpg' || $extensao == 'png'){
                                                                            $ex = $extensao;
                                                                        }else{
                                                                            $ex = 'blank-image';
                                                                        }
                                                                        $existe++;
                                                                        
                                                                        echo '<div class="card h-100" style="border: 1px solid var(--bs-card-border-color)" >
                                                                                <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                                                                    <a href="'.$_SESSION['URL'].'/file/ticket/'.$row_arq['TA_ANEXO'].'" class="text-gray-800 text-hover-primary d-flex flex-column" download>
                                                                                        <div class="symbol symbol-50px mb-5">
                                                                                            <img src="'.$_SESSION['URL'].'/assets/media/svg/files/'.$ex.'.svg" class="theme-light-show" alt="">
                                                                                            <img src="'.$_SESSION['URL'].'/assets/media/svg/files/'.$ex.'-dark.svg" class="theme-dark-show" alt="">
                                                                                        </div>
                                                                                        <div class="fw-bold mb-2" style="font-size: 10px;">
                                                                                            '.$row_arq['TA_ANEXO'].'                          
                                                                                        </div>
                                                                                    </a>
                                                                                </div>
                                                                            </div>';
                                                                    }
                                                                    if($existe == 0){
                                                                        echo 'Nenhum arquivo escontrado';
                                                                    }
                                                                    ?>
                                                                    </div>

                                                                    <!--begin::Accordion-->
                                                                    <div class="accordion accordion-icon-toggle" id="kt_accordion_2">

                                                                     <?php if(sizeof($array_ticket) > 0){ 
                                                                        $i = 0;
                                                                        foreach($array_ticket as $t){
                                                                            $anexo = null;
                                                                            
                                                                            $query_resp = $Conexao->query("SELECT TA_ANEXO FROM TICKET_ANEXO WHERE TA_TID = '".$t['T_ID']."'");
                                                                            while ($row_resp = $query_resp->fetch()) {
                                                                                $anexo .= '<a href="'.$t['T_URL'].'/file/ticket/'.$row_resp['TA_ANEXO'].'" class="btn btn-sm btn-light me-2 mb-2" target="_blank" download><i class="fa-solid fa-download fa-fw"></i> Baixar Arquivo</a>';
                                                                            }
                                                                            $i++; 
                                                                            echo '<div class="mb-5">
                                                                                <!--begin::Header-->
                                                                                <div class="accordion-header py-3 d-flex '.(($i == sizeof($array_ticket)) ? '' : 'collapsed').'" data-bs-toggle="collapse" data-bs-target="#kt_accordion_2_item_'.$i.'">
                                                                                    <span class="accordion-icon">
                                                                                        <i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i>
                                                                                    </span>
                                                                                    <h3 class="fs-4 fw-semibold mb-0 ms-4 '.($t['RESPONDIDOPOR'] != $_SESSION['USUID']).'">Respondido por: '.$t['RESPONDIDOPOR'].'</h3> <span style="margin-left: auto;"> '.$t['T_DATA2'].'</span>
                                                                                </div>
                                                                                <div id="kt_accordion_2_item_'.$i.'" class="fs-6 collapse '.(($i == sizeof($array_ticket)) ? 'show' : '').' ps-10" data-bs-parent="#kt_accordion_2">
                                                                                    '.$t['T_DESCRICAO'].'<br>'.$anexo.'
                                                                                </div>
                                                                            </div>';
                                                                            }
                                                                        }?>
                                                              
                                                                    </div>
                                                                    <!--end::Accordion-->
                                                                    <div class="separator separator-dotted separator-content border-warning my-15">
                                                                        <i class="fa-regular fa-paper-plane fa-fw fs-2" style="color: #ffc700;"></i>
                                                                    </div>
                                                                    <!--end::Description-->
                                                                    <!--begin::Row-->
                                                                    <form class="row g-3" id="form" <?php echo ($status == 'F') ? 'style="display:none"' : ''?>>
                                                                    <label class="required fw-semibold fs-6 mb-2">Selecione a ação</label>
                                                                        <div class="col-md-6 fv-row">
                                                                            <select class="form-select" name="tipo" id="tipo" >
                                                                                <option value="A">Adicionar Observação</option>
                                                                                <?php if($_SESSION['USUID'] == $criadoporid){
                                                                                echo '<option value="F">Finalizar</option>
                                                                                    <option value="C">Cancelar</option>';
                                                                                }?>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <input class="form-control" type="file" id="file" name="file[]" multiple>
                                                                        </div>
                                                                        <div class="col-md-12 fv-row">
                                                                            <textarea class="form-control" rows="6" name="desc" id="desc" placeholder="Escreva a resposta ou observação..."></textarea>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <button type="button" class="btn btn-primary me-10" id="btn-submit" style="float: right;">
                                                                                <span class="indicator-label">
                                                                                    Enviar
                                                                                </span>
                                                                                <span class="indicator-progress">
                                                                                    Processando... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                                                </span>
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <!--end::Details-->
                                                            </div>
                                                            <!--end::Ticket view-->
                                                        </div>
                                                        <!--end::Content-->
                                                        <!--begin::Sidebar-->
                                                        
                                                        </div>
                                                        <!--end::Layout-->
                                                    </div>
                                                    <!--end::Card body-->
                                                </div>
                                                    <!--end::Card-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end:: -----------------------------------FINALIZA -------------------------------------->
										</div>
                                        
									</div>
								</div>
								<!--end::Content container-->
							</div>
							<!--end::Content-->
						</div>
						<!--end::Content wrapper-->
						<!--begin::Footer-->
						<?php require $_SERVER['DOCUMENT_ROOT']."/menu/footer.php"; ?>
						<!--end::Footer-->
					</div>
					<!--end:::Main-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Drawers-->
		<?php require $_SERVER['DOCUMENT_ROOT']."/menu/footer-js.php"; ?>
	</body>
	<!--end::Body-->
</html>
<link href="<?php echo $_SESSION['URL'] ?>/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo $_SESSION['URL'] ?>/assets/plugins/custom/datatables/datatables.bundle.js"></script>

<script>

const form = document.getElementById("form");

var validator = FormValidation.formValidation(
    form,
    {
        fields: {
            "tipo": {
                validators: {
                    notEmpty: {
                        message: "Campo obrigatório."
                    }
                }
            },
            "desc": {
                validators: {
                    notEmpty: {
                        message: "Campo obrigatório."
                    }
                }
            },
        },

        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: ".fv-row",
                eleInvalidClass: "",
                eleValidClass: ""
            })
        }
    }
);



// Submit button handler
const submitButton = document.getElementById("btn-submit");
submitButton.addEventListener("click", function (e) {
    // Prevent default button action
    e.preventDefault();

    // Validate form before submit
    if (validator) {
        validator.validate().then(function (status) {
            console.log("validated!");

            if (status == "Valid") {
                // Show loading indication
                submitButton.setAttribute("data-kt-indicator", "on");

                // Disable button to avoid multiple click
                submitButton.disabled = true;

                // Simulate form submission. For more info check the plugin"s official documentation: https://sweetalert2.github.io/
                setTimeout(function () {
                    // Remove loading indication
					
                    const formData = new FormData();
                    formData.append('action', 'responder');
                    formData.append('id', $('#idticket').val());
                    formData.append('tipo',  $('#tipo').val());
                    formData.append('desc', $('#desc').val());

                    var ins = document.getElementById('file').files.length;
                    for (var x = 0; x < ins; x++) {
                        formData.append("file[]", document.getElementById('file').files[x]);
                    }
                  

                    $.post({
                        url: "handle.php", // the resource where youre request will go throw
                        type: "POST", // HTTP verb
                        data: formData,
                        contentType: false,
                        processData: false,
						success: function (response) {
							if(response == '1'){
								
								Swal.fire({
									text: "Ticket respondido",
									icon: "success",
									buttonsStyling: false,
									showConfirmButton: false,
                                    timer: 1500
								}).then((result) => {
                                    location.reload();
								})
								
							}else {
								toastr["error"]("Ocorreu um erro. "+response);
							}
							submitButton.removeAttribute("data-kt-indicator");
							// Enable button
							submitButton.disabled = false;
						}
					});
                }, 2000);
            }else{
				Swal.fire({
					text: "Preencha todos os campos obrigatórios.",
					icon: "error",
					buttonsStyling: false,
					confirmButtonText: "Ok",
					customClass: {
						confirmButton: "btn btn-primary"
					}
				});
			}
        });
    }
});

$('body').on('click', '.btn-download-anexo', function(e) {
    
    var id_download = $(this).attr("data-id");

    var a = document.createElement('a');
    a.href ='<?php echo  $_SESSION['URL'] ?>'+'/file/ticket/'+id_download.trim();
    a.download = id_download.trim();
    document.body.append(a);
    a.click();
    a.remove();
});

</script>