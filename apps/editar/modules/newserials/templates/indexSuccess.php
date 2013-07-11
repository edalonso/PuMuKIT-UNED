<h3 class="cab_body_div"><img src="/images/admin/cab/serial_ico.png"/> Series Multimedia</h3>

 <script type='text/javascript' src="http://static.jstree.com/v.1.0pre/jquery.hotkeys.js"></script>
 <script type='text/javascript' src="http://static.jstree.com/v.1.0pre/jquery.cookie.js"></script>
<div class="arbol-jquery" style="float:left; width: 780px">
<div id="description">
<div style="height:30px; overflow:auto;" id="mmenu">
  <input type="button" style="display:block; float:left;" value="add folder" id="add_folder">
  <input type="button" style="display:block; float:left;" value="add file" id="add_default">
  <input type="button" style="display:block; float:left;" value="rename" id="rename">
  <input type="button" style="display:block; float:left;" value="remove" id="remove">
  <!--<input type="button" style="display:block; float:left;" value="cut" id="cut">
  <input type="button" style="display:block; float:left;" value="copy" id="copy">
  <input type="button" style="display:block; float:left;" value="paste" id="paste">-->
  <input type="button" style="display:block; float:right;" value="clear" id="clear_search">
  <input type="button" style="display:block; float:right;" value="search" id="search">
  <input type="text" style="display:block; float:right;" value="" id="text">
</div>

                               <!-- the tree container (notice NOT an UL node) -->
<div style="height:200px; overflow: auto" class="jstree jstree-5 jstree-default jstree-focused" id="uvigotvTree"></div>
<div style="height:30px; text-align:center;">
                               <input type="button" onclick="$.get('reconstruct', function () { $('#uvigotvTree').jstree('refresh',-1); });" value="reconstruct" id="reconstruct" style="width:170px; height:24px; margin:5px auto;">
                               <input type="button" onclick="$('#alog').load('analyze');" value="analyze" id="analyze" style="width:170px; height:24px; margin:5px auto;">
                               <input type="button" onclick="$('#uvigotvTree').jstree('refresh',-1);" value="refresh" id="refresh" style="width:170px; height:24px; margin:5px auto;">
</div>
<div style="border:1px solid gray; padding:5px; height:100px; margin-top:15px; overflow:auto; font-family:Monospace;" id="alog">
</div>

<!-- JavaScript neccessary for the tree -->
<script class="source below" type="text/javascript">
$(function () {
  $("#uvigotvTree").bind("before.jstree", function (e, data) {
    $("#alog").append(data.func + "<br />");
  })
  .delegate("a","click", function(e) {
          if ($(this).parent().hasClass('jstree-leaf')){
              $.ajax({
                  async : false,
                          type: 'GET',
                          url:  "<?php echo url_for("newserials/previewMms") ?>",
                          data : { 
                          "id" : $(this).parent().attr("id").split("_")[1]
                              }, 
                          success : function (r) {
                          $('#preview').html(r);
                      }
                  });
              $.ajax({
                  async : false,
                          type: 'GET',
                          url:  "<?php echo url_for("newserials/editMms") ?>",
                          data : { 
                          "id" : $(this).parent().attr("id").split("_")[1]
                              }, 
                          success : function (r) {
                          $('#edit').html(r);
                      }
                  });
          } else {
              $.ajax({
                  async : false,
                          type: 'GET',
                          url:  "<?php echo url_for("newserials/edit") ?>",
                          data : { 
                          "id" : $(this).parent().attr("id").split("_")[1]
                              }, 
                          success : function (r) {
                          $('#edit').html(r);
                      }
                  });
              $.ajax({
                  async : false,
                          type: 'GET',
                          url:  "<?php echo url_for("newserials/preview") ?>",
                          data : { 
                          "id" : $(this).parent().attr("id").split("_")[1]
                              }, 
                          success : function (r) {
                          $('#preview').html(r);
                      }
                  });
          }
  })


  .jstree({ 
  // List of active plugins
    "plugins" : ["themes","json_data","ui","crrm","cookies","dnd","search","types","hotkeys","contextmenu"],

  // I usually configure the plugin that handles the data first
  // This example uses JSON as it is most common
  "json_data" : { 
  // This tree is ajax enabled - as this is most common, and maybe a bit more complex
  // All the options are almost the same as jQuery's AJAX (read the docs)
      "ajax" : {
      // the URL to fetch the data
        "url" : "<?php echo url_for("newserials/list2") ?>",
      // the `data` function is executed in the instance's scope
      // the parameter is the node being loaded 
      // (may be -1, 0, or undefined when loading the root nodes)
        "data" : function (n) { 
          // the result is fed to the AJAX request `data` option
          return { 
              "operation" : "get_children", 
              "id" : n.attr ? n.attr("id").replace("node_","") : 1
          }; 
        }
      }
  },
  // Configuring the search plugin
  "search" : {
      // As this has been a common question - async search
      // Same as above - the `ajax` config option> is actually jQuery's AJAX object
      "ajax" : {
          "url" :  "<?php echo url_for("newserials/search") ?>",
              // You get the search string as a parameter
              "data" : function (str) {
              return { 
                  "operation" : "search", 
                      "search_str" : str 
              }; 
          }
      }
  },
  // Using types - most of the time this is an overkill
  // read the docs carefully to decide whether you need types
        "types" : {
            // I set both options to -2, as I do not need depth and children count checking
            // Those two checks may slow jstree a lot, so use only when needed
            "max_depth" : -2,
                "max_children" : -2,
                // I want only `drive` nodes to be root nodes 
                // This will prevent moving or creating any other type as a root node
                "valid_children" : [ "drive" ],
                "types" : {
                // The default type
                "default" : {
                    // I want this type to have no children (so only leaf nodes)
                    // In my case - those are files
                    "valid_children" : "none",
                        // If we specify an icon for the default type it WILL OVERRIDE the theme icons
                        "icon" : {
                        "image" : "/images/admin/file.png"
                            }
                },
                    // The `folder` type
                "folder" : {
                        // can have files and other folders inside of it, but NOT `drive` nodes
                        "valid_children" : [ "default", "folder" ],
                            "icon" : {
                            "image" : "/images/admin/folder.png"
                                }
                },
                        // The `drive` nodes 
                "drive" : {
                            // can have files and folders inside, but NOT other `drive` nodes
                            "valid_children" : [ "default", "folder" ],
                                "icon" : {
                                "image" : "/images/admin/root.png"
                                    },
                                // those prevent the functions with the same name to be used on `drive` nodes
                                // internally the `before` event is used
                                "start_drag" : false,
                                    "move_node" : false,
                                    "delete_node" : false,
                                    "remove" : false
                }
            }
        },
        // UI &amp; core - the nodes to initially select and open will be overwritten by the cookie plugin
  
        // the UI plugin - it handles selecting/deselecting/hovering nodes
        "ui" : {
            // this makes the node with ID node_4 selected onload
            "initially_select" : [ "node_4" ]
                },
        // the core plugin - not many options here
        "core" : { 
            // just open those two nodes up
            // as this is an AJAX enabled tree, both will be downloaded from the server
            "initially_open" : [ "node_2" , "node_3" ] 
                }
  })
  .bind("create.jstree", function (e, data) {
        $.post(
                "<?php echo url_for("newserials/list2") ?>", 
               { 
                   "operation" : "create_node", 
                   "id" : data.rslt.parent.attr("id").replace("node_",""), 
                   "position" : data.rslt.position,
                   "title" : data.rslt.name,
                   "type" : data.rslt.obj.attr("rel")
               }, 
               function (r) {//Cambio para que no recargue y se vea el nuevo nodo
                   if(!r.status) {
                       $(data.rslt.obj).attr("id", "node_" + r.id);
                   }
                   else {
                       $.jstree.rollback(data.rlbk);
                   }
               }
               );
    })
  .bind("remove.jstree", function (e, data) {
        data.rslt.obj.each(function () {
                $.ajax({
                    async : false,
                            type: 'POST',
                            url:  "<?php echo url_for("newserials/list2") ?>",
                            data : { 
                            "operation" : "remove_node", 
                                "id" : this.id.replace("node_","")
                                }, 
                            success : function (r) {
                            if(!r.status) {
                                data.inst.refresh();
                            }
                        }
                    });
            });
    })
  .bind("rename.jstree", function (e, data) {
        $.post(
                "<?php echo url_for("newserials/list2") ?>", 
               { 
                 "operation" : "rename_node", 
                 "id" : data.rslt.obj.attr("id").replace("node_",""),
                 "title" : data.rslt.new_name
               }, 
               function (r) {
                   if(!r.status) {//No funciona, es el array pero el campo status no existe, construyo mal la respuesta
                       //$.jstree.rollback(data.rlbk);
                   }
               }
               );
    })
  .bind("move_node.jstree", function (e, data) {
        data.rslt.o.each(function (i) {
                $.ajax({
                    async : false,
                            type: 'POST',
                            url:  "<?php echo url_for("newserials/list2") ?>",
                            data : { 
                                "operation" : "move_node", 
                                "id" : $(this).attr("id").replace("node_",""), 
                                "ref" : data.rslt.cr === -1 ? 1 : data.rslt.np.attr("id").replace("node_",""), 
                                "position" : data.rslt.cp + i,
                                "title" : data.rslt.name,
                                "copy" : data.rslt.cy ? 1 : 0
                                },
                              success : function (r) {
                              if(!r.status) {//Eliminé esta acción porque volvía al estado anterior aunque el nodo se movía
                                //$.jstree.rollback(data.rlbk);
                              }
                              else {
                                  $(data.rslt.oc).attr("id", "node_" + r.id);
                                  if(data.rslt.cy && $(data.rslt.oc).children("UL").length) {
                                      data.inst.refresh(data.inst._get_parent(data.rslt.oc));
                                  }
                              }
                              $("#analyze").click();
                             }
                    });
            });
    });

});

// Code for the menu buttons
$(function () { 
        $("#mmenu input").click(function () {
                switch(this.id) {
                  case "add_default":
                  case "add_folder":
                    $("#uvigotvTree").jstree("create", null, "last", { "attr" : { "rel" : this.id.toString().replace("add_", "") } });
                    break;
                  case "search":
                      $("#uvigotvTree").jstree("search", document.getElementById("text").value);
                      break;
                  case "text": break;
                  default:
                      $("#uvigotvTree").jstree(this.id);
                      break;
                }
        });
});
</script>
</div>
</div>
<div id="tv_admin_container" style="margin-right: 0px;">
 <div id="tv_admin_content" style="margin-left: 900px;margin-right: 0px;">
    <div id="list_serials" name="list_serials" act="/newserials/list">
      <?php include_component('newserials', 'list') ?>
    </div>

    <div style="float:right; width:50%">
      <ul class="tv_admin_actions">
        <!-- Falta -->
        <li>
         <?php echo link_to_function('Wizard', "Modalbox.show('".url_for("wizard/serial")."',{width:800, title:'PASO I: Serie'})", 'class=tv_admin_action_next') ?> 
        </li>
        <li>
         <?php echo link_to_remote('Crear', array('before' => '$("filter_serials").reset();', 'update' => 'list_serials', 'url' => 'serials/create?filter=filter', 'script' => 'true'), array('title' => 'Crear nueva seria', 'class' => 'tv_admin_action_create')) ?>
        </li>
      </ul>
    </div>

    <select id="options_serials" style="margin: 10px 0px; width: 33%" title="Acciones sobre elementos selecionados" onchange="window.change_select('serial', 'options_serials')">
      <option value="default" selected="selected">Seleciona una acci&oacute;n...</option>
      <option disabled="">---</option>
      <option value="delete_sel">Borrar selecionados</option>
      <option value="inv_announce_sel">Anunciar/Desanunciar selecionados</option>
      <!-- <option value="inv_working_sel">Ocultar/Desocultar selecionados</option> Ocultarlos todos -->
    </select>
    
  </div>
  <div style="clear:both"></div>

</div>
</div>
</div>

<!-- div editar -->
<div id="tv_admin_container" style="margin-right: 0px;">
<div id="edit_serials" class="tv_admin_edit">
  <div id="tv_admin_bar" style="width: 800px; margin-top: 50px;">
    <div id="preview_serial" style="min-height:74px; padding:5px; border: solid 1px #DDD; background: #8eb0bc url(/images/admin/cab/cab.gif) repeat-x scroll 0% 0%; margin-bottom: 10px; height: 300px;">
    <div id="preview"></div>
    </div>
    
    <?php include_partial('acordeon', array('name' => 'serial', 'broadcasts' => $broadcasts, 'serialtypes' => $serialtypes)) ?> 

  </div>
  <div id="edit"></div>
</div>