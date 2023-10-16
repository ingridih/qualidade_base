<?php 

require_once $_SERVER['DOCUMENT_ROOT']."/menu/session.php";

if($_SESSION['USUTIPO'] != 'QUALIDADE'){ 
    header('Location: /login'); 
}

if(!isset($_SESSION['USUADMIN'])){
    $usuadmin = 0;
}else{
    $usuadmin = $_SESSION['USUADMIN'];
}

if(isset($_GET['data'])){
    $data = $_GET['data'];
}else{
    $data = date('d/m/Y', strtotime('-30 days')).' até '.date('d/m/Y', strtotime('+1 days'));
}


?>
<!DOCTYPE html>
<!--
oooooooo-----ooo---ooo---ooo
ooo---ooo----ooo---ooo---ooo
ooo---oooo---ooo---ooooooooo
ooo---ooo----ooo---ooo---ooo
ooooooo------ooo---ooo---ooo
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
									<!--begin::Row-->
									<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
										<!--begin::Col-->
										<div class="col-xxl-12">
											<!--begin::Engage widget 10-->
											<div class="card card-flush h-md-100">
												<!--begin::Body-->
												<div class="card-body d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0">
													<!--begin::Wrapper-->
													<div class="mb-10">
														<!--begin::Title-->
														<h1 class="anchor fw-bold mb-5">
                                                            Granulometria
                                                        </h1>

                                                        <div class="card-header align-items-center py-5 gap-2">
                                                            <!--begin::Card title-->
                                                            <div class="card-title">
                                                                <!--begin::Search-->
                                                                <div class="d-flex align-items-center position-relative my-1">
                                                                    <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span class="path1"></span><span class="path2"></span></i>
                                                                    <input type="text" data-kt-docs-table-filter="search" style="margin-right: 10px;" class="form-control form-control-solid w-250px ps-15" placeholder="Buscar"/>
                                                                </div>
                                                                <!--end::Search-->
                                                            </div>
                                                            <!--end::Card title-->
                                                            <!--begin::Card toolbar-->
                                                            <div class="card-toolbar flex-row-fluid gap-5">
                                                                <!--begin::Flatpickr-->
                                                                <div class="input-group w-250px">
                                                                    <input type="text" data-kt-docs-table-filter="date" class="form-control form-control-solid w-250px ps-10 flatpicker data" placeholder="Data de Envio" value="<?php echo $data?>"/>  
                                                                </div>
                                                                <!--end::Add product-->
                                                            </div>
                                                            <a href="#" class="btn btn-dark config-granu" data-bs-toggle="modal" data-bs-target="#modal_gg"><i class="fa-solid fa-gears fa-fw fs-2"></i>Configuração</a>
                                                            <a href="#" class="btn btn-primary criar-granu" data-bs-toggle="modal" data-bs-target="#kt_modal_granu"><i class="fa-solid fa-plus fa-fw fs-2"></i>Nova Granulometria</a>
                                                            <!--end::Card toolbar-->
                                                        </div>

                                                        <!--end::Wrapper-->
                                                        <table id="kt_datatable_example_1" class="table align-middle table-row-dashed fs-7 gy-5">
                                                            <thead>
                                                            <tr class="text-start text-gray-400 fw-bold fs-8 text-uppercase gs-0">
                                                                <th>ID</th>
                                                                <th>Produto</th>
                                                                <th>Identificação</th>
                                                                <th>Data</th>
                                                                <th>Excel</th>
                                                                <th>Ação</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody class="text-gray-600 fw-semibold">
                                                            </tbody>
                                                        </table>
													</div>
												</div>
												<!--end::Body-->
											</div>
											<!--end::Engage widget 10-->
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
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
        <script src="<?php echo  $_SESSION['URL']?>/assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script src="<?php echo  $_SESSION['URL']?>/assets/js/flatpicker-pt.js"></script>
        <script src="<?php echo  $_SESSION['URL']?>/assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>

        <div class="modal fade" tabindex="-1" id="modal_gg">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Configuração de peso e peneira.</h3>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body">
                        <div class="text-center">
                            <div class="spinner-border text-primary" id="loadingSpinner" role="status">
                            <span class="visually-hidden">Carregando...</span>
                            </div>
                        </div>
                        <div class="div_gg_dados"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                        <button id="btn-config-salvar" type="button" class="btn btn-primary">
                            <span class="indicator-label">
                                Salvar
                            </span>
                            <span class="indicator-progress">
                                Processando... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- MODAL DE GRANULOEMETRIA -->
        <div class="modal fade" tabindex="-1" id="kt_modal_granu">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Granulometria</h3>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body">
                        <div class="text-center">
                            <div class="spinner-border text-primary" id="loadingSpinner2" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                        </div>
                        <div class="granu_div"></div>
                            
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                        <button id="btn-granu" type="button" class="btn btn-primary">
                            <span class="indicator-label">
                                Salvar
                            </span>
                            <span class="indicator-progress">
                                Processando... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        
	</body>
	<!--end::Body-->
</html>

<style>
    .hidden {
        display: none;
    }
    @keyframes pulse-border {
  0% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(255, 165, 0, 0.7);
  }
  70% {
    box-shadow: 0 0 0 10px rgba(255, 165, 0, 0);
  }
  100% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(255, 165, 0, 0);
  }
}

.pulse-span {
  display: inline-block;
  animation: pulse-border 2s infinite;
}

.dataTables_processing{
    background-color:white !important;
}

.badge-tipo {
    max-width: 120px;
    width: 120px;
}
.reprovado-peneira{
    color:red;
}

</style>
<script>
$(".flatpicker").flatpickr({
    dateFormat: "d/m/Y",
    mode: "range",
    locale: "pt",
    rangeSeparator: ' até ',
    
});

$('.data').on('change', function() {
    var selectedDate = $(this).val();
    var hasUntilKeyword = selectedDate.toLowerCase().includes('até');
    if (hasUntilKeyword) {
        window.location.href = "analysis?data="+selectedDate;
    } 
});



"use strict";

// Class definition
var KTDatatablesServerSide = function () {
    // Shared variables
    var table;
    var dt;
    var filterPayment;

    // Private functions
    var initDatatable = function () {
        dt = $("#kt_datatable_example_1").DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
            },
            processing: true,
            serverSide: true,
            order: [[0, 'desc']],
            stateSave: false,
            searchDelay: 500,
            
            ajax: {
                url: "list.php?data="+$('.data').val(),
                type: 'POST'
            },
            columns: [
                { data: 'id' },
                { data: 'prod_id' },
                { data: 'identifica' },
                { data: 'data' },
                { data: 'excel' },
                { data: 'botao' },
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: function (data, type, row) {
                        return `<span class="badge badge-light-primary">${data}</span>`;
                    }
                },
                {
                    targets: 4,
                    render: function (data, type, row) {
                        return `<a href="#" class="btn btn-success btn-sm excel-gran" id="${data}">
                            <i class="fa-solid fa-download fa-fw"></i>Baixar XLS</a>`;
                    }
                },
                {
                    targets: 5,
                    render: function (data, type, row) {
                        return `<a href="#" class="btn btn-danger btn-sm apagar-gran" id="${data}">
                            <span class="indicator-label">
                                <i class="fa-solid fa-trash fa-fw"></i>Apagar
                            </span>
                            <span class="indicator-progress">
                                Processando... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </a>`;
                    }
                },
            ],
            // Add data-filter attribute
           
        });

        table = dt.$;

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        dt.on('draw', function () {
            KTMenu.createInstances();
        });
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector('[data-kt-docs-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            var searchValue = $(this).val();

            // Realiza a busca no DataTable
            dt.search(searchValue).draw();
        });
    }
   

    // Public methods
    return {
        init: function () {
            initDatatable();
            handleSearchDatatable();
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    KTDatatablesServerSide.init();
});



// configurar granulometria - pesos e aberturas.
$('body').on('click', '.config-granu',
    function(e) {
        $(".div_gg_dados").empty();
        $.post({
        url: "handle.php", // the resource where youre request will go throw
        type: "POST", // HTTP verb
        data: {action: 'config_gran'},
        success: function (response) {
            var result = JSON.parse(response);
            $('#loadingSpinner').removeClass('visible').addClass('invisible');
            $(".div_gg_dados").append(result);

            $('#kt_docs_repeater_basic').repeater({
                initEmpty: false,
                show: function () {
                    $(this).slideDown();
                    $(this).find('input').val('');
                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });
        }
    });
});


// salvar configuração da peneira.
$('body').on('click', '#btn-config-salvar', function(e) {

    var dados = [];
    // Seleciona todos os elementos com a classe "pai"
    $('.espec').each(function(indexPai) {
    // Obtém o valor do pai
        var espec = $(this).val();

        // Seleciona o elemento filho correspondente (com base na posição)
        var abertura = $('.abertura').eq(indexPai).val();
        var peso = $('.peso').eq(indexPai).val();
        var regra = $('.regra').eq(indexPai).val();
        var valor_regra = $('.valor_regra').eq(indexPai).val();
        // Adiciona os valores e posições ao array
        dados.push({
            espec: espec,
            abertura: abertura,
            peso: peso,
            regra: regra,
            valor_regra: valor_regra
        });
    });

    $.post({
        url: "handle.php", // the resource where youre request will go throw
        type: "POST", // HTTP verb
        data: {action: 'salvar-config', dados: dados},
        success: function (response) {
            if(response != 0){
                toastr["success"]("Configuração salva com sucesso.");
                setTimeout(function () {
                    $('#modal_gg').modal('hide'); 
                }, 1000);
            }else{
                toastr["error"]("Ocorreu um erro. "+response);
            }
        }
    });
});

// Granulometria valores
$('body').on('click', '.criar-granu',
    function(e) {
        $(".granu_div").empty();
        $.post({
            url: "handle.php", // the resource where youre request will go throw
            type: "POST", // HTTP verb
            data: {action: 'granulometria-criar'},
            success: function (response) {
                var result = JSON.parse(response);
                $('#loadingSpinner2').removeClass('visible').addClass('invisible');
                $(".granu_div").append(result);
                $('.gran_op').select2();
            }
            
        });
});

// ----------- calculo granulometria  --------------
function calcular_gran(){
    var total1 = 0;
    $('.input_valor').each(function(index) {
        var valor = $(this).val();
        var peso = $('.peso').eq(index).val();
        if (!isNaN(valor) && !isNaN(peso) && valor !== '' && peso !== '') {
            var i = valor - peso;
            $('#i'+index).html(i);
            total1 += i;
        }
    });
    $('#v6').html(total1);
    if(total1 > 0){
        $totalk = 0;
        $('.plv').each(function(index) {
            var valorPi = parseFloat($('#i'+index).text());
            var k = valorPi/total1*100;
            var l0 = 0;
            $('#k'+index).html(k.toFixed(2));
            $totalk += k;
            if ($(this).hasClass('fundo')) {
                $('#l'+index).html($('#k'+index).text());
            }else{
                if(index == 0){
                    $('#l'+index).html(k.toFixed(2));
                    l0 = k;
                }else{
                    var anterior = parseFloat($('#l'+(index-1)).text());
                    var vall = k + anterior;
                    $('#l' + index).html(vall.toFixed(2));
                }
            }
        });
        $('#v7').html($totalk.toFixed(2));
        // ultima coluna
        $('.pnv').each(function(index) {
            if ($(this).hasClass('fundo')) {
                $('#n' + index).html('*');
            }else{
                if(index == 0){

                    // --- regra para deixar vermelho no limite definido, coluna passante (%)
                    const regraPeneira = $('#regra_peneira' + index).val();
                    const regraValor = parseFloat($('#regra_valor' + index).val());
                    const kValue = parseFloat($('#k' + index).text());
                    const vall = 100 - kValue;

                    if (regraPeneira && regraValor !== null) {
                        if ((regraPeneira === 'menor' && vall < regraValor) ||
                            (regraPeneira === 'maior' && vall > regraValor) ||
                            (regraPeneira === 'entre')) {
                            const [valorMin, valorMax] = regraValor.toString().split(',');
                            if (vall >= parseFloat(valorMin) && vall <= parseFloat(valorMax)) {
                                $('#tk' + index).css('color', 'red');
                            } else {
                                $('#tk' + index).css('color', 'black');
                            }
                        }
                    }
                    /// ----------------  fim da regra --------------
                    $('#n' + index).html(100-parseFloat($('#k'+index).text()).toFixed(2));
                    $('#tk' + index).html(100-parseFloat($('#k'+index).text()).toFixed(2));
                }else{
                    var anterior = parseFloat($('#l'+(index)).text());
                    var vall = 100 - anterior;
                    if ($('#regra_peneira' + index).val() !== null && $('#regra_valor' + index).val() !== null) {
                        if (($('#regra_peneira' + index).val() === 'menor' && vall < $('#regra_valor' + index).val()) ||
                            ($('#regra_peneira' + index).val() === 'maior' && vall > $('#regra_valor' + index).val()) ||
                            ($('#regra_peneira' + index).val() === 'entre')) {
                            const valorcorte = $('#regra_peneira' + index).val().split(',');
                            if (vall >= parseFloat(valorcorte[0]) && vall <= parseFloat(valorcorte[1])) {
                                $('#tk' + index).css('color', 'red');
                            } else {
                                $('#tk' + index).css('color', 'black');
                            }
                        }
                    }
                    
                    $('#n' + index).html(vall.toFixed(2));
                    $('#tk' + index).html(vall.toFixed(2));
                }
            }
        });
        $('.coll_topo').each(function(index) {
            $('#tl' + index).html($('#k'+(index)).text());
        });
    }

}




$(document).on('change', '.input_valor', function (e) {
    calcular_gran();    
});

// salvar dados da grsanulometria.
const submitSalvar = document.getElementById('btn-granu');
$('body').on('click', '#btn-granu',
    function(e) {
        
        if($('#prod_id').val() != '' && $('#gran_desc').val() != ''){
            
            submitSalvar.setAttribute('data-kt-indicator', 'on');
            submitSalvar.disabled = true;

            
            const topo = [];
            const passante_topo = [];
            const retido_topo = [];
            $('.cole_topo').each(function(index) {
                topo.push($('.cole_topo').eq(index).html());
                passante_topo.push($('.colk_topo').eq(index).html());
                retido_topo.push($('.coll_topo').eq(index).html());
            });

            const abertura = [];
            const peso = [];
            const input = [];
            const prod_retido = [];
            const percentual = [];
            const cumu_retido = [];
            const peso_passante = [];
            $('.col_abertura').each(function(index) {
                abertura.push($('.col_abertura').eq(index).html());
                peso.push($('.peso').eq(index).val());
                input.push($('.input_valor').eq(index).val());
                prod_retido.push($('.piv').eq(index).html());
                percentual.push($('.pkv').eq(index).html());
                cumu_retido.push($('.plv').eq(index).html());
                peso_passante.push($('.pnv').eq(index).html());
            });
            prod_retido.push($('#v6').html());
            percentual.push($('#v7').html());

            $.post({
                url: "handle.php", // the resource where youre request will go throw
                type: "POST", // HTTP verb
                data: {action: 'salvar_granu', prod_id: $('#prod_id').val(), gran_desc: $('#gran_desc').val(), topo: topo, passante_topo: passante_topo, retido_topo: retido_topo, 
                    abertura: abertura, peso: peso, input: input, prod_retido: prod_retido, percentual: percentual, cumu_retido: cumu_retido, peso_passante: peso_passante},
                success: function (response) {
                    if(response != 0){
                        toastr["success"]("Dados salvos com sucesso.");
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    }else{
                        toastr["error"]("Ocorreu um erro. "+response);
                    }
                    submitSalvar.removeAttribute('data-kt-indicator');
                    submitSalvar.disabled = false;
                }
            });
        }else{
            Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'preencha todos os campos',
            })
        }
});

// baixar excel
$('body').on('click', '.excel-gran',
    function(e) {
        Swal.fire({
            icon: 'info',
            title: 'Carregando...',
            text: 'Gerando relatório!',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false
        })
        $.post({
            url: "handle.php", // the resource where youre request will go throw
            type: "POST", // HTTP verb
            data: {action: 'gerar_xls', id: $(this).attr("id")},
            success: function (response) {
                if(response != 0){
                    var downloadLink = '<?php echo  $_SESSION['URL'] ?>'+'/file/ct/'+response;
                    var a = document.createElement('a');
                    a.href = downloadLink;
                    a.download = response; // Defina o nome do arquivo
                    a.style.display = 'none';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }else{
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Ocorreu um erro' +response,
                    })
                }
                
            }
        });
});

// apagar registro da granulometria
$('body').on('click', '.apagar-gran',
    function(e) {
    Swal.fire({
        title: 'Tem certeza que deseja apagar o registro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        showCancelButton: true,
        }).then((result) => {
        if (result.isConfirmed) {
            var id = $(this).attr("id");
            $.post({
                url: "handle.php", // the resource where youre request will go throw
                type: "POST", // HTTP verb
                data: {action: 'apagar-granulometria', id: id},
                success: function (response) {
                    if(response == 1){
                        Swal.fire(
                        'Apagado!',
                        'Registro apagado com sucesso.',
                        'success'
                        )
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    }else{
                        Swal.fire(
                        'Erro!',
                        'Não foi possivel apagar o registro.'+response,
                        'error'
                        )
                    }
                    
                }
                
            });
        } 
    })
    
});
</script>