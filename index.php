<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cacimba – WebGIS ECOGIS</title>

	<link rel="stylesheet" href="styles.css">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.0/leaflet.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="source/jquery-ui.min.css">
	<link rel="stylesheet" href="plugins/sidebar/leaflet-sidebar.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
	<link rel="stylesheet" href="https://ppete2.github.io/Leaflet.PolylineMeasure/Leaflet.PolylineMeasure.css" />
	<link rel="stylesheet" href="plugins/mouseposition/L.Control.MousePosition.css">
	<link
	  rel="stylesheet"
	  href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css"
	/>
	<link rel="stylesheet" href="plugins/minimap/Control.MiniMap.min.css">



	<script src ="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.0/leaflet.js"></script>
	<script src ="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.0/leaflet-src.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
	<script src="source/jquery-ui.min.js"></script>
	<script src="plugins/sidebar/leaflet-sidebar.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>
	<script src="https://ppete2.github.io/Leaflet.PolylineMeasure/Leaflet.PolylineMeasure.js"></script>
	<script src='plugins/mouseposition/L.Control.MousePosition.js'></script>
	<script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.js"></script>
	<script src='plugins/minimap/Control.MiniMap.min.js'></script>
	<script src='plugins/ajax/leaflet.ajax.js'></script>

	<style>
	    .bold {
	        font-weight: bold;
	        font-size: 16px;
	    }
	    .errorMsg{
	        padding:0;
	        text-align: center;
	        background-color: darksalmon;
	    }

        .new_feature{
            display:none;
        }

        .successMsg{
            text-align:center;
            background-color: #85c1e9;
            font-weight: bold;

        }

	    /* Autocomplete fix */
	    .ui-autocomplete {
	        max-height: 220px;
	        overflow-y: auto;
	        overflow-x: hidden;
	        z-index: 99999;
	        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
	    }
        

	    .sidebar-content {
	        overflow: visible !important;
	    }

        /* FIX: prevent close arrow from blocking Home icon when sidebar collapses */
        .sidebar.collapsed .sidebar-close {
            display: none;
        }
	</style>

</head>
<body>
	
   <div id="sidebar" class="sidebar collapsed">
        <!-- Nav tabs -->  
        <!-- icons can be found on font awesome cheatsheet --> 
        <div class="sidebar-tabs">

            <ul role="tablist">
                <li><a href="#inicio" role="tab"><i class="fa fa-home"></i></a></li> 
                <li><a href="#nascente" role="tab"><i class="fa-solid fa-droplet"></i></a></li>
                <li><a href="#rio" role="tab"><i class="fa-solid fa-bridge-water"></i></a></li>
                <li><a href="#lago" role="tab"><i class="fa-solid fa-water"></i></a></li>
                
            </ul>

        </div>

        <!-- Tab panes --> 
        <div class="sidebar-content">
            <div class="sidebar-pane" id="inicio">
                <h1 class="sidebar-header">
                    Início
                    <span class="sidebar-close"><i class="fa fa-caret-left"></i></span>
                </h1>

                <div style="text-align:center; margin:20px 0 10px;">
                    <img src="img/logo-ecogis.png" alt="ECOGIS" style="max-width:180px; height:auto;" onerror="this.style.display='none'">
                </div>

                <h3 style="text-align:center; margin:10px 0;">Cacimba</h3>
                <p style="text-align:center; margin-top:0;"><b>WebGIS de Recursos Hídricos</b></p>

                <p style="text-align:justify;">
                    Esta plataforma reúne num único mapa interativo informação sobre as nascentes,
                    os rios e os lagos da região. Clique em cada elemento para ver o tipo, a qualidade
                    da água, o estado de conservação e o uso, procure um recurso pelo nome e registe
                    novos dados diretamente no mapa.
                </p>
                <p style="text-align:justify;">
                    A ECOGIS disponibiliza esta ferramenta para apoiar o conhecimento,
                    a gestão e a proteção da água.
                </p>

                <hr>
                <p style="font-size:12px; color:#666; text-align:center;">
                    Use os ícones à esquerda para consultar e adicionar
                    <i class="fa-solid fa-droplet"></i> nascentes,
                    <i class="fa-solid fa-bridge-water"></i> rios e
                    <i class="fa-solid fa-water"></i> lagos.<br><br>
                    &copy; ECOGIS
                </p>
            </div>


            <div class="sidebar-pane" id ="nascente">
                <h1 class="sidebar-header">
                    Nascentes
                    <span class="sidebar-close"><i class="fa fa-caret-left"></i></span>
                </h1>
                <div id="divNascentes" class="col-xs-12">
                    <div class="col-xs-12" style="height: 10px;"></div>
                    <div class="col-xs-12">
                        <p class="text-center bold">Informação da nascente</p>
                    </div> 
                    <div id="erro_nascentes" class="errorMsg col-xs-12"></div>

                    <div class="col-xs-12" style="height: 10px;"></div>


                    <div class="form-group">
                        <div class="col-xs-6">
                            <input type="text" id="nome_nascente" class="form-control" placeholder="Nome da nascente">
                        </div>
                        <div class="col-xs-6">
                            <button id="encontreNascente" class="btn btn-primary btn-block">Encontre a nascente</button>
                        </div>

                    <div class="col-xs-12" style="height: 20px;"></div>

                    <div class="col-xs-12" id="informacao_nascente">

                    </div>

                <div class="col-xs-12" style="heigth:60px;"></div>

                <div id="novaNascente" class="col-xs-12">
                    <div class="col-xs-8">
                        <button type="button" class="btn btn-info btn-block" id="btn_nascente_form">Inserir nova nascente</button>
                    </div>
                    <div class="col-xs-4">
                        <button type="button" class="btn btn-success btn-block" id="btn_nascente_refresh">Refrescar </button>
                    </div>

                    <div class="col-xs-12" style="height:20px;"></div>

                    <div id="informacao_nova_nascente" class="col-xs-12 new_feature">
                        <label class="control-label col-sm-4" for="nome_nova_nascente">Nome</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="nome_nova_nascente" id="nome_nova_nascente">
                        </div>

                        <div class="col-xs-12" style="height:10px;"></div>

                        <label class="control-label col-sm-4" for="tipo_nascente">Tipo</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="tipo_nascente" id="tipo_nascente">
                                <option value=""></option>
                                <option value="Permanente">Permanente</option>
                                <option value="Sazonal">Intermitente</option>
                            </select>
                        </div>

                        <div class="col-xs-12" style="height:10px;"></div>

                        <label class="control-label col-sm-4" for="estado_nascente">Estado</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="estado_nascente" id="estado_nascente">
                                <option value=""></option>
                                <option value="Degradado">Degradado</option>
                                <option value="Conservado">Conservado</option>
                            </select>
                        </div>

                        <div class="col-xs-12" style="height:10px;"></div>

                        <label class="control-label col-sm-4" for="qual_agua">Qualidade da água</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="qual_agua" id="qual_agua">
                                <option value=""></option>
                                <option value="Boa">Boa</option>
                                <option value="Média">Média</option>
                                <option value="Ruim">Ruim</option>
                            </select>
                        </div>

                        <div class="col-xs-12" style="height:10px;"></div>

                        <label class="control-label col-sm-4" for="uso">Uso</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="uso" id="uso">
                                <option value=""></option>
                                <option value="Domestico">Doméstico</option>
                                <option value="Irrigacao">Irrigação</option>
                                <option value="Pesca">Pesca</option>
                                <option value="Recreação">Lazer</option>
                            </select>
                        </div>


                        <div class="col-xs-12" style="height:10px;"></div>

                        <label class="control-label col-sm-4" for="nascente_geometry">Geometria</label>
                        <div class="col-sm-8">
                            <textarea name="nascente_geometry" id="nascente_geometry" disabled></textarea>
                        </div>

                        <div class="col-xs-12" style="height:10px;"></div>

                        <div class="col-xs-6">
                            <button type="button" class="btn btn-danger btn block" id="btn_nascente_cancel">Cancelar</button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-success btn-block" id="btn_nascente_insert">Inserir nascente</button>
                        </div>
                        <div class="col-xs-12" style="height:10px;"></div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="height:10px;"></div>
                <div id="status_nascente" class="col-xs-12 successMsg"></div>
                        
                    </div>                   
                </div>
            </div>



            <div class="sidebar-pane" id ="rio">
                <h1 class="sidebar-header">
                    Rios
                    <span class="sidebar-close"><i class="fa fa-caret-left"></i></span>
                </h1>
                <div id="divNascentes" class="col-xs-12">
                    <div class="col-xs-12" style="height: 10px;"></div>
                    <div class="col-xs-12">
                        <p class="text-center bold">Informação do rio</p>
                    </div> 
                    <div id="erro_rios" class="errorMsg col-xs-12"></div>

                    <div class="col-xs-12" style="height: 10px;"></div>


                    <div class="form-group">
                        <div class="col-xs-6">
                            <input type="text" id="nome_rio" class="form-control" placeholder="Nome do rio">
                        </div>
                        <div class="col-xs-6">
                            <button id="encontreRio" class="btn btn-primary btn-block">Encontre o rio</button>
                        </div>

                    <div class="col-xs-12" style="height: 20px;"></div>

                    <div class="col-xs-12" id="informacao_rio">
                    </div>

                <div class="col-xs-12" style="heigth:60px;"></div>

                <div id="novoRio" class="col-xs-12">
                    <div class="col-xs-8">
                        <button type="button" class="btn btn-info btn-block" id="btn_rio_form">Inserir novo rio</button>
                    </div>

                    <div class="col-xs-4">
                        <button type="button" class="btn btn-success btn-block" id="btn_rio_refresh">Refrescar</button>
                    </div>

                    <div class="col-xs-12" style="height:20px;"></div>

                    <div id="informacao_novo_rio" class="col-xs-12 new_feature">
                        <label class="control-label col-sm-4" for="nome_novo_rio">Nome</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="nome_novo_rio" id="nome_novo_rio">
                        </div>

                        <div class="col-xs-12" style="height:10px;"></div>

                        <label class="control-label col-sm-4" for="tipo_rio">Tipo</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="tipo_rio" id="tipo_rio">
                                <option value=""></option>
                                <option value="Permanente">Permanente</option>
                                <option value="Sazonal">Intermitente</option>
                            </select>
                        </div>

                        <div class="col-xs-12" style="height:10px;"></div>

                        <label class="control-label col-sm-4" for="estado_rio">Estado</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="estado_rio" id="estado_rio">
                                <option value=""></option>
                                <option value="Degradado">Degradado</option>
                                <option value="Conservado">Conservado</option>
                            </select>
                        </div>

                        <div class="col-xs-12" style="height:10px;"></div>

                        <label class="control-label col-sm-4" for="qual_agua_rio">Qualidade da água</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="qual_agua_rio" id="qual_agua_rio">
                                <option value=""></option>
                                <option value="Boa">Boa</option>
                                <option value="Média">Média</option>
                                <option value="Ruim">Ruim</option>
                            </select>
                        </div>

                        <div class="col-xs-12" style="height:10px;"></div>

                        <label class="control-label col-sm-4" for="uso_rio">Uso</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="uso_rio" id="uso_rio">
                                <option value=""></option>
                                <option value="Domestico">Doméstico</option>
                                <option value="Irrigacao">Irrigação</option>
                                <option value="Pesca">Pesca</option>
                                <option value="Recreação">Lazer</option>
                            </select>
                        </div>


                        <div class="col-xs-12" style="height:10px;"></div>

                        <label class="control-label col-sm-4" for="rio_geometry">Geometria</label>
                        <div class="col-sm-8">
                            <textarea name="rio_geometry" id="rio_geometry" disabled></textarea>
                        </div>

                        <div class="col-xs-12" style="height:10px;"></div>

                        <div class="col-xs-6">
                            <button type="button" class="btn btn-danger btn block" id="btn_rio_cancel">Cancelar</button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-success btn-block" id="btn_rio_insert">Inserir rio</button>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="height:10px;"></div>
                <div id="status_rio" class="col-xs-12 successMsg"></div>
                        
                    </div>                   
                </div>
            </div>



            <div class="sidebar-pane" id ="lago">
                <h1 class="sidebar-header">
                    Lagos
                    <span class="sidebar-close"><i class="fa fa-caret-left"></i></span>
                </h1>
                <div id="divLagos" class="col-xs-12">
                    <div class="col-xs-12" style="height: 10px;"></div>
                    <div class="col-xs-12">
                        <p class="text-center bold">Informação do lago</p>
                    </div> 
                    <div id="erro_lagos" class="errorMsg col-xs-12"></div>

                    <div class="col-xs-12" style="height: 10px;"></div>


                    <div class="form-group">
                        <div class="col-xs-6">
                            <input type="text" id="nome_lago" class="form-control" placeholder="Nome do lago">
                        </div>
                        <div class="col-xs-6">
                            <button id="encontreLago" class="btn btn-primary btn-block">Encontre o lago</button>
                        </div>

                    <div class="col-xs-12" style="height: 20px;"></div>

                    <div class="col-xs-12" id="informacao_lago">
                    </div>

                <div class="col-xs-12" style="heigth:60px;"></div>

                <div id="novoLago" class="col-xs-12">
                    <div class="col-xs-8">
                        <button type="button" class="btn btn-info btn-block" id="btn_lago_form">Inserir novo lago</button>
                    </div>
                    <div class="col-xs-4">
                        <button type="button" class="btn btn-success btn-block" id="btn_lago_refresh">Refrescar</button>
                    </div>

                    <div class="col-xs-12" style="height:20px;"></div>

                    <div id="informacao_novo_lago" class="col-xs-12 new_feature">
                        <label class="control-label col-sm-4" for="nome_novo_lago">Nome</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="nome_novo_lago" id="nome_novo_lago">
                        </div>

                        <div class="col-xs-12" style="height:10px;"></div>


                        <label class="control-label col-sm-4" for="origem_novo_lago">Origem</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="origem_novo_lago" id="origem_novo_lago">
                                <option value=""></option>
                                <option value="Natural">Natural</option>
                                <option value="Artificial">Artificial</option>
                            </select>
                            
                        </div>

                        <div class="col-xs-12" style="height:10px;"></div>



                        <label class="control-label col-sm-4" for="tipo_lago">Tipo</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="tipo_lago" id="tipo_lago">
                                <option value=""></option>
                                <option value="Reservatório">Reservatório</option>
                                <option value="Lagoa">Lagoa</option>
                                <option value="Lago">Lago</option>
                                <option value="Barragem">Barragem</option>
                                <option value="Permanente">Permanente</option>
                            </select>
                        </div>

                        <div class="col-xs-12" style="height:10px;"></div>

                        <label class="control-label col-sm-4" for="estado_lago">Estado</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="estado_lago" id="estado_lago">
                                <option value=""></option>
                                <option value="Degradado">Degradado</option>
                                <option value="Conservado">Conservado</option>
                            </select>
                        </div>

                        <div class="col-xs-12" style="height:10px;"></div>

                        <label class="control-label col-sm-4" for="qual_agua_lago">Qualidade da água</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="qual_agua_lago" id="qual_agua_lago">
                                <option value=""></option>
                                <option value="Boa">Boa</option>
                                <option value="Média">Média</option>
                                <option value="Ruim">Ruim</option>
                            </select>
                        </div>

                        <div class="col-xs-12" style="height:10px;"></div>

                        <label class="control-label col-sm-4" for="uso_lago">Uso</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="uso_lago" id="uso_lago">
                                <option value=""></option>
                                <option value="Domestico">Doméstico</option>
                                <option value="Irrigacao">Irrigação</option>
                                <option value="Pesca">Pesca</option>
                                <option value="Recreação">Lazer</option>
                            </select>
                        </div>


                        <div class="col-xs-12" style="height:10px;"></div>

                        <label class="control-label col-sm-4" for="lago_geometry">Geometria</label>
                        <div class="col-sm-8">
                            <textarea name="lago_geometry" id="lago_geometry" disabled></textarea>
                        </div>

                        <div class="col-xs-12" style="height:10px;"></div>

                        <div class="col-xs-6">
                            <button type="button" class="btn btn-danger btn block" id="btn_lago_cancel">Cancelar</button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-success btn-block" id="btn_lago_insert">Inserir lago</button>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="height:10px;"></div>
                <div id="status_lago" class="col-xs-12 successMsg"></div>
                        
                    </div>                   
                </div>
            </div>

	



            <div class="sidebar-pane" id="profile">
                <h1 class="sidebar-header">Profile<span class="sidebar-close"><i class="fa fa-caret-left"></i></span></h1>
            </div>

            <div class="sidebar-pane" id="messages">
                <h1 class="sidebar-header">Messages<span class="sidebar-close"><i class="fa fa-caret-left"></i></span></h1>
            </div>

            <div class="sidebar-pane" id="settings">
                <h1 class="sidebar-header">Settings<span class="sidebar-close"><i class="fa fa-caret-left"></i></span></h1>
            </div>
        </div>
    </div>


	<div id="mapdiv" class="col-md-12"></div>

	<script>

		var baseLayers;
		var overlays;
		var mymap;
		var lyrSearch;

		var layerNascentes;
		var layerRios;
		var layerLagos;


		var lista_nascentes = [];
		var lista_rios = [];
		var lista_lagos = [];
		

		// MY MAP
		mymap = L.map('mapdiv', {
		    center: [-12.776, 15.734],
		    zoom: 13,
		    attributionControl:false,
		    zoomControl:false
		});


		//BASEMAPS

		var GoogleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
				                maxZoom: 23,
				                subdomains:['mt0','mt1','mt2','mt3']
				                });
	
		var openStreetMap = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png?', {maxZoom:22});

		var CartoDB_Positron = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {maxZoom:22});

		var Esri_WorldImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {maxZoom: 22});

		var Esri_WorldImagery_minimap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {maxZoom: 22});




		var OpenTopoMap = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
		            maxZoom: 23,
		            attribution: 'Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'
		        });


		GoogleStreets.addTo(mymap);





		//SIDEBAR

		var sidebar = L.control.sidebar('sidebar', {
		    position: 'left'
		});


		mymap.addControl(sidebar);
		
		//EASY BUTTON
		
		L.easyButton('glyphicon-transfer', function(){sidebar.toggle();
		}).addTo(mymap);

//ZOOM CONTROL
		L.control.zoom({position:'topright'}).addTo(mymap);

		//LAYER CONTROL

		var baseLayers = {
		    "Google Streets": GoogleStreets,
		    "OSM": openStreetMap,
		    "CartoDB Positron": CartoDB_Positron,
		    "ESRI World Imagery": Esri_WorldImagery,
		    "Open Topo Map": OpenTopoMap
		};

		//Display control layers and overlays

		var control_layers = L.control.layers(baseLayers, overlays).addTo(mymap);


		
		//POLYGON MEASURE

		L.control.polylineMeasure({position:'topright'}).addTo(mymap);

		//MOUSE POSITION
		
		L.control.mousePosition({position:'bottomright'}).addTo(mymap);

		// draw control or Geoman (marker, line, polygon, etc)
		mymap.pm.addControls({  
		  position: 'topright',
		  drawMarker: true,
		  drawPolyline: true,
		  drawPolygon: true,  
		  drawCircleMarker: false,
		  rotateMode: false,
		  drawCircle: false,
		  drawText: false,
		  drawRectangle: false,
		  editMode: false,
		  dragMode: false,
		  cutPolygon: false,
		  removeMode: true
		}); 

        //Get shape and latlon from geoman drawings and convert to geojson
		
        mymap.on("pm:create",function(e){
            var jsn = e.layer.toGeoJSON().geometry;
            console.log(e.shape);
            var jsn_modified;
            
            if (e.shape == 'Marker'){

                if(confirm("Deseja adicionar esta nova nascente?")){
                    jsn_modified = {type:'Point', coordinates:jsn.coordinates};
                    $("#nascente_geometry").val(JSON.stringify(jsn_modified)); 
                    }         
            }

            if (e.shape == 'Line'){

                if(confirm("Deseja adicionar este novo rio?")){
                    jsn_modified = {type:'LineString', coordinates:jsn.coordinates};
                    $("#rio_geometry").val(JSON.stringify(jsn_modified));
                    }         
            }

            if (e.shape == 'Polygon'){

                if(confirm("Deseja adicionar este novo lago?")){
                    jsn_modified = {type:'MultiPolygon', coordinates:[jsn.coordinates]};
                    $("#lago_geometry").val(JSON.stringify(jsn_modified));
                    }         
            }

        });


		//MINIMAP

		var miniMap = new L.Control.MiniMap(Esri_WorldImagery_minimap,{position:'bottomright',height:150,width:150}).addTo(mymap);

		//SCALE

		L.control.scale({position:'bottomright', maxWidth:'200',imperial:false}).addTo(mymap);

		//DATA LOADING OPERATIONS


		//Home
		load_nascentes();
		load_rios();
		load_lagos();

		//LOADING POINTS NASCENTES

		
		function load_nascentes(){
			$.ajax({
				url: 'load_data.php',
				data: {table:'nascentes_02'},
				type: 'POST',
				success: function(response){
					if(response.trim().substr(0,5)=='ERROR'){
						console.log(response);
					} else{
						var jsnNascente= JSON.parse(response);

						if (layerNascentes){
							layerNascentes.remove();
							control_layers.removeLayer(layerNascentes);
						}

						layerNascentes = L.geoJSON(jsnNascente, {pointToLayer:style_nascentes}).addTo(mymap);
						control_layers.addOverlay(layerNascentes,"Nascentes");
						mymap.fitBounds(layerNascentes.getBounds());

                        						
					}
				},
				error: function(xhr, status, error) {
					console.log("ERROR: "+error);
				}
			});
		}



        function refresh_nascentes(){
            $.ajax({
                url: 'load_data.php',
                data: {table:'nascentes_02'},
                type: 'POST',
                success: function(response){
                    if(response.trim().substr(0,5)=='ERROR'){
                        console.log(response);
                    } else{
                        var jsnNascente= JSON.parse(response);

                        if (layerNascentes){
                            layerNascentes.remove();
                            control_layers.removeLayer(layerNascentes);
                        }

                        layerNascentes = L.geoJSON(jsnNascente, {pointToLayer:style_nascentes}).addTo(mymap);
                        control_layers.addOverlay(layerNascentes,"Nascentes");                             
                    }
                },
                error: function(xhr, status, error) {
                    console.log("ERROR: "+error);
                }
            });
        }


        $("#nome_nascente").autocomplete({
            source:lista_nascentes
        });



		function style_nascentes(json,latlng) {
			var att = json.properties;
			var color;
			var fill_color;
			var radius = 5;
			var fillOpacity = 1;

			
			lista_nascentes.push(att.nome);
		
			switch (att.est_conserv) {
				case 'Conservado':
					color = '#162456';
					fill_color ='#162456';
					break;
				case 'Degradado':
					color = '#4B004F';
					fill_color ='#4B004F';
					break;
				default:
					color = '#0000ff';
					fill_color ='#0000ff';
					break;
			}

			var style = {radius:radius, color:color, fillColor:fill_color, fillOpacity:fillOpacity};
			
			return L.circleMarker(latlng,style).bindTooltip("Nome da nascente:"+att.nome+"<br>Tipo:"+att.tipo+"<br>Vazão média:"+att.vaz_med+" l/hora"+"<br>Qualidade da água:"+att.qual_agua+"<br>Estado de conservação:"+att.est_conserv+"<br>Uso:"+att.uso);

		}


		//SIDEBAR FIND POINTS


		$('#encontreNascente').click(function(){
			var nome_nascente = $("#nome_nascente").val();

			returnLayerByAttribute("nascentes_02","nome",nome_nascente, function(lyr){
				if (lyr) {
					
					var att = lyr.feature.properties;

					if (lyrSearch){
						lyrSearch.remove();
					}

					lyrSearch = L.circle(lyr.getLatLng(), {radius:60, color:'red', weight:10,opacity:0.6, fillOpacity:0.3}).addTo(mymap);

					mymap.setView(lyr.getLatLng(),17);

					layerNascentes.bringToFront();
					$("#informacao_nascente").html("Municipio:"+att.municipio+"<br>Tipo:"+att.tipo+"<br>Vazão média (l/min):"+att.vaz_med+"<br>Estado de Conservação:"+att.est_conserv+"<br>Qualidade da água:"+att.qual_agua);


				} else {
					$("#erro_nascentes").html("Nascente não encontrada!");
				}

			});

		});

        $("#btn_nascente_form").click(function(){
            $("#informacao_nova_nascente").show();
        });

        $("#btn_nascente_cancel").click(function(){
            $("#informacao_nova_nascente").hide();
        });

        $("#btn_nascente_insert").click(function(){
            var nome_nova_nascente =$("#nome_nova_nascente").val();
            var tipo_nascente =$("#tipo_nascente").val();
            var estado_nascente =$("#estado_nascente").val();
            var qual_agua =$("#qual_agua").val();
            var uso =$("#uso").val();
            var nascente_geometry =$("#nascente_geometry").val();

            if(nome_nova_nascente ==""||tipo_nascente==""||estado_nascente==""||nascente_geometry==""){
                $("#status_nascente").html("Por favor preencha todos os campos");
            } else{
                $.ajax({
                    url:'insert_data.php',
                    data:{nome_nova_nascente:nome_nova_nascente,tipo_nascente:tipo_nascente,estado_nascente:estado_nascente,qual_agua:qual_agua,uso:uso,nascente_geometry:nascente_geometry,request:'nascentes'},
                    type:'POST',
                    success:function(response){
                        if(response.trim().substr(0,5)=='ERROR'){
                            console.log(response);
                            $("#status_nascente").html(response);
                        } else{
                            $("#status_nascente").html("Nascente inserida com sucesso!");
                            refresh_nascentes();

                            $("#nome_nova_nascente").val("");
                            $("#tipo_nascente").val("");
                            $("#estado_nascente").val("");
                            $("#qual_agua").val("");
                            $("#uso").val("");
                            $("#nascente_geometry").val("");
 
                        }
                    },
                    error:function(xhr,status,error){
                        $("#status_nascente").html(error);
                    }
                });

            }
        });

        $("#btn_nascente_refresh").click(function(){
            refresh_nascentes();
        });









        



		//ADD LINE FEATURES style: will load the style, oneachfeature: function to pop-up tooltip

		//rios.on('data:loaded', function(){
		//    $("#Rio_id").autocomplete({
		//        source: lista_rios
		//    });
		//});


		function load_rios() {
			$.ajax({
				url: 'load_data.php',
				data: {table:'waterlines'},
				type: 'POST',
				success: function(response){
					if(response.trim().substr(0,5)=='ERROR'){
						console.log(response);
					} else{
						var jsnRio= JSON.parse(response);

						if (layerRios){
							layerRios.remove();
							control_layers.removeLayer(layerRios);
						}

						layerRios = L.geoJSON(jsnRio, {style:style_rivers, onEachFeature:process_rivers}).addTo(mymap);
						control_layers.addOverlay(layerRios,"Rios");	
					}
				},
				error: function(xhr, status, error) {
					console.log("ERROR: "+error);
				}
			});
		}


		$("#nome_rio").autocomplete({
			source:lista_rios
		});





		function style_rivers(json) {
			var att=json.properties;

			switch (att.tipo) {
				case 'Permanente':
					color ='#53EAFD';
					break;
				case 'Intermitente':
					color ='#CEFAFE';
					break;
				default:
					color = '#17202A';
					break;
			}
			return {color:color};
		}

		function process_rivers(json,lyr){
			var att = json.properties;

			lista_rios.push(att.nome);

			lyr.bindTooltip("Nome do rio:"+att.nome+"<br>Tipo:"+att.tipo+"<br>Vazão média:"+att.fluxo_med+" l/hora"+"<br>Qualidade da água:"+att.qual_agua+"<br>Estado de conservação:"+att.est_conserv+"<br>Uso:"+att.uso);

		}



		//sidebar content river information

		$("#encontreRio").click(function(){
			var nome_rio = $("#nome_rio").val();
			
			returnLayerByAttribute("waterlines", "nome", nome_rio, function(lyr){
			
			if (lyr) {
				var att = lyr.feature.properties;

				if (lyrSearch) {
					lyrSearch.remove();
				}

				lyrSearch = L.geoJSON(lyr.toGeoJSON(), {style:{color:'red', weight:10, opacity:0.5}}).addTo(mymap);

				mymap.fitBounds(lyr.getBounds());
				layerRios.bringToFront();
					$("#informacao_rio").html("Nome do rio:"+att.nome+"<br>Tipo:"+att.tipo+"<br>Vazão média:"+att.fluxo_med+" l/hora"+"<br>Qualidade da água:"+att.qual_agua+"<br>Estado de conservação:"+att.est_conserv+"<br>Uso:"+att.uso);
			} else {
				$("#erro_rios").html("Rio não encontrado!");
			}

			});

		});


        $("#btn_rio_form").click(function(){
            $("#informacao_novo_rio").show();
        });

        $("#btn_rio_cancel").click(function(){
            $("#informacao_novo_rio").hide();
        });


        $("#btn_rio_insert").click(function(){
            var nome_novo_rio =$("#nome_novo_rio").val();
            var tipo_rio =$("#tipo_rio").val();
            var estado_rio =$("#estado_rio").val();
            var qual_agua_rio =$("#qual_agua_rio").val();
            var uso_rio =$("#uso_rio").val();
            var rio_geometry =$("#rio_geometry").val();

            if(nome_novo_rio ==""||tipo_rio==""||estado_rio==""||rio_geometry==""){
                $("#status_rio").html("Por favor preencha todos os campos");
            } else{
                $.ajax({
                    url:'insert_data.php',
                    data:{nome_novo_rio:nome_novo_rio,tipo_rio:tipo_rio,estado_rio:estado_rio,qual_agua_rio:qual_agua_rio,uso_rio:uso_rio,rio_geometry:rio_geometry,request:'rios'},
                    type:'POST',
                    success:function(response){
                        if(response.trim().substr(0,5)=='ERROR'){
                            console.log(response);
                            $("#status_rio").html(response);
                        } else{
                            $("#status_rio").html("Rio inserido com sucesso!");
                            load_rios();

                            $("#nome_novo_rio").val("");
                            $("#tipo_rio").val("");
                            $("#estado_rio").val("");
                            $("#qual_agua_rio").val("");
                            $("#uso_rio").val("");
                            $("#rio_geometry").val("");
                            
                        }
                    },
                    error:function(xhr,status,error){
                        $("#status_rio").html(error);
                    }
                });

            }
        });

        $("#btn_rio_refresh").click(function(){
            load_rios();
        });












		//POLYGONS Water bodies
		
		function load_lagos(){
			$.ajax({
				url: 'load_data.php',
				data: {table:'polygons'},
				type: 'POST',
				success: function(response){
					if(response.trim().substr(0,5)=='ERROR'){
						console.log(response);
					} else{
						var jsnLago= JSON.parse(response);

						if (layerLagos){
							layerLagos.remove();
							control_layers.removeLayer(layerLagos);
						}

						layerLagos = L.geoJSON(jsnLago, {style:style_lakes, onEachFeature:process_lakes}).addTo(mymap);
						control_layers.addOverlay(layerLagos,"Lagos");	
					}
				},
				error: function(xhr, status, error) {
					console.log("ERROR: "+error);
				}
			});


		}
	
		$("#nome_lago").autocomplete({
		    source: lista_lagos
		});


		//$("#Lago_id").autocomplete({
		//	source:lista_lagos
		//});

		function style_lakes(json) {
			var att = json.properties;
			var Area = att.area;
			var color;
			var fill_color;
			var fill_opacity = 1

			switch (att.tipo) {
				case 'Lago':
					if (Area >= 6039) {
						color ='#000075';
						fill_color ='#000075';
					} else if (Area >= 6 ) {
						color ='#0000A3';
						fill_color ='#0000A3';
					} else if (Area >= 4 ) {
						color ='#0000D1';
						fill_color ='#0000D1';
					} else if (Area >= 2 ) {
						color ='#0000FF';
						fill_color ='#0000FF';
					} else if (Area >= 1 ) {
						color ='#2E2EFF';
						fill_color ='#2E2EFF';
					} else {
						color ='#5C5CFF';
						fill_color ='#5C5CFF';
					} 
				
					
					break;
				case 'Lagoa':
					color ='#CEFAFE';
					fill_color ='#53EAFD';
					fill_opacity = 0.8
					break;
				case 'Reservatório':
					color ='#CEFAFE';
					fill_color ='#53EAFD';
					fill_opacity = 0.8
					break;
				case 'Rio':
					color ='#CEFAFE';
					fill_color ='#53EAFD';
					fill_opacity = 0.8
					break;
				default:
					color = 'black';
					fill_color ='black'
					break;
			}

			return {color:color, fillColor: fill_color, fillOpacity:fill_opacity};

		}


		function process_lakes(jsn,lyr) {
			var att = jsn.properties;

			lista_lagos.push(att.nome);

			lyr.bindPopup("Nome:"+att.nome+"<br>Tipo:"+att.tipo+"<br>Origem:"+att.origem+"<br>Uso:"+att.uso);
			}

			//Add polygon layer control to mymap



		//sidebar content for lakes
		
		$("#encontreLago").click(function(){
			var nome_lago = $("#nome_lago").val();

			returnLayerByAttribute("polygons", "nome", nome_lago, function(lyr){
				if (lyr) {
					var att = lyr.feature.properties;

					if (lyrSearch) {
						lyrSearch.remove();
					}

					lyrSearch = L.geoJSON(lyr.toGeoJSON(), {style:{color:'red', weight:10, opacity:1, fillOpacity:0}}).addTo(mymap);

					mymap.fitBounds(lyr.getBounds());
				
					$("#informacao_lago").html("Nome:"+att.nome+"<br>Tipo:"+att.tipo+"<br>Origem:"+att.origem+"<br>Uso:"+att.uso);
				} else {
					$("#erro_lagos").html("Lago não encontrado!");
				}



			});

			

		});

        $("#btn_lago_form").click(function(){
            $("#informacao_novo_lago").show();
        });

        $("#btn_lago_cancel").click(function(){
            $("#informacao_novo_lago").hide();
        });

        $("#btn_lago_insert").click(function(){
            var nome_novo_lago =$("#nome_novo_lago").val();
            var origem_novo_lago =$("#origem_novo_lago").val();
            var tipo_lago =$("#tipo_lago").val();
            var estado_lago =$("#estado_lago").val();
            var qual_agua_lago =$("#qual_agua_lago").val();
            var uso_lago =$("#uso_lago").val();
            var lago_geometry =$("#lago_geometry").val();

            if(nome_novo_lago ==""||origem_novo_lago ==""|| tipo_lago==""||estado_lago==""||lago_geometry==""){
                $("#status_lago").html("Por favor preencha todos os campos");
            } else{
                $.ajax({
                    url:'insert_data.php',
                    data:{nome_novo_lago:nome_novo_lago,origem_novo_lago:origem_novo_lago,tipo_lago:tipo_lago,estado_lago:estado_lago,qual_agua_lago:qual_agua_lago,uso_lago:uso_lago,lago_geometry:lago_geometry,request:'lagos'},
                    type:'POST',
                    success:function(response){
                        if(response.trim().substr(0,5)=='ERROR'){
                            console.log(response);
                            $("#status_lago").html(response);
                        } else{
                            $("#status_lago").html("Lago inserido com sucesso!");
                            load_lagos();

                            $("#nome_novo_lago").val("");
                            $("#origem_novo_lago").val("");
                            $("#tipo_lago").val("");
                            $("#estado_lago").val("");
                            $("#qual_agua_lago").val("");
                            $("#uso_lago").val("");
                            $("#lago_geometry").val("");
                            
                        }
                    },
                    error:function(xhr,status,error){
                        $("#status_lago").html(error);
                    }
                });

            }
        });

        $("#btn_lago_refresh").click(function(){
            load_lagos()
        });





		//GENERAL FUNCTIONS
		function returnLayerByAttribute(table,field,value, callback){
			$.ajax({
				url:'find_data.php',
				data: {table:table, field:field, value:value},
				type:'POST',
				success: function(response){
					if(response.trim().substr(0,5)=='ERROR'){
						console.log(response);
						callback(false);
					} else {
						var jsn = JSON.parse(response);
						var layer = L.geoJSON(jsn);
						var layers = layer.getLayers();

						if (layers.length>0) {
							callback(layers[0]);
						} else{
							callback(false);
						}
					}
				},

				error: function(xhr, status, error) {
					console.log("ERROR: "+error);
				}

			})


		}







	</script>

</body>
</html>