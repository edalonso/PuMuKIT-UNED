<h3 class="cab_body_div"><img src="/images/admin/cab/serial_ico.png"/> Series Virtuales</h3>

<div class="arbol-jquery" style="float:left; width: 35%">
<div id="description">
<div style="height:30px; overflow:auto;" id="mmenu">
  <!--<input type="button" style="display:block; float:left;" value="add folder" id="add_folder">
  <input type="button" style="display:block; float:left;" value="add file" id="add_default">
  <input type="button" style="display:block; float:left;" value="rename" id="rename">
  <input type="button" style="display:block; float:left;" value="remove" id="remove">
  <input type="button" style="display:block; float:left;" value="cut" id="cut">
  <input type="button" style="display:block; float:left;" value="copy" id="copy">
  <input type="button" style="display:block; float:left;" value="paste" id="paste">
  <input type="button" style="display:block; float:right;" value="clear" id="clear_search">
  <input type="button" style="display:block; float:right;" value="search" id="search">
  <input type="text" style="display:block; float:right;" value="" id="text">-->
</div>

                               <!-- the tree container (notice NOT an UL node) -->
<div style="height:350px; overflow: auto" class="jstree-default" id="uvigotvTree"></div>
<!--<div style="height:30px; text-align:center;">
                               <input type="button" onclick="$.get('reconstruct', function () { $('#uvigotvTree').jstree('refresh',-1); });" value="reconstruct" id="reconstruct" style="width:170px; height:24px; margin:5px auto;">
                               <input type="button" onclick="$('#alog').load('analyze');" value="analyze" id="analyze" style="width:170px; height:24px; margin:5px auto;">
                               <input type="button" onclick="$('#uvigotvTree').jstree('refresh',-1);" value="refresh" id="refresh" style="width:170px; height:24px; margin:5px auto;">
</div>-->
<!-- <div style="border:1px solid gray; padding:5px; height:100px; margin-top:15px; overflow:auto; font-family:Monospace;" id="alog">-->
</div>

</div>
</div>

<div id="tv_admin_container" style="margin-right: 0px;">
 <div id="tv_admin_content" style="margin-left: 40%;margin-right: 0px;">
    <div id="list_serials" name="list_serials" style="margin-top: 20px">
    <?php include_component('serials', 'mmsList') ?>
    </div>
     <table style="background: rgb(221, 221, 221); margin: 10px 0px 0px 0px">
     <tr class="hR"></tr>
     </table>
  </div>
  <div style="clear:both"></div>
</div>
</div>
</div>

<!-- div editar -->
<div id="tv_admin_container" style="margin-right: 0px;">
<div id="edit_serials" class="tv_admin_edit">
  <div id="tv_admin_bar" style="width: 25%; margin-top: 50px;">
    <div id="preview_serial" style="min-height:74px; padding:5px; border: solid 1px #DDD; background: #8eb0bc url(/images/admin/cab/cab.gif) repeat-x scroll 0% 0%; margin-bottom: 10px; height: 350px;">
    <div id="preview"></div>
    </div>
    
    <?php //include_partial('acordeon', array('name' => 'serial', 'broadcasts' => $broadcasts, 'serialtypes' => $serialtypes)) ?> 

  </div>
  <div id="edit" style="width: 60%"></div>
</div>


<script class="source below" type="text/javascript">
$(function () {
  $("#uvigotvTree").bind("before.jstree", function (e, data) {
    $("#alog").append(data.func + "<br />");
  })
  .delegate("a","click", function(e) {
              $.ajax({
                  async : false,
                          type: 'GET',
                          url: "<?php echo url_for("serials/mmsList") ?>",
                          data : { 
                          "id" : $(this).parent().attr("id").split("_")[1],
                          "page" : 1
                              }, 
                          success : function (r) {
                          $('.tv_admin_list').html(r);
                          $('#no_serial').hide();
                      }
                  });
      })


  .jstree({ 
  // List of active plugins
          //"plugins" : ["themes","json_data","ui","crrm","cookies","dnd","search","types","hotkeys","contextmenu"], //Eliminando el plugin contextmenu no se despliega un menú con botón derecho
          "plugins" : ["themes","json_data","ui","crrm","cookies","dnd","search","types","hotkeys"],

  // I usually configure the plugin that handles the data first
  // This example uses JSON as it is most common
  "json_data" : { 
  // This tree is ajax enabled - as this is most common, and maybe a bit more complex
  // All the options are almost the same as jQuery's AJAX (read the docs)
      "ajax" : {
      // the URL to fetch the data
        "url" : "<?php echo url_for("serials/list2") ?>",
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
          "url"  :"<?php echo url_for("serials/search") ?>",
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
                        //"image" : "/images/admin/icons/video_icon.png"//Para que el último archivo muestre icono de video
                        "image" : "/images/admin/folder.png"
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
               "<?php echo url_for("serials/list2") ?>",
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
                            url: "<?php echo url_for("serials/list2") ?>",
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
               "<?php echo url_for("serials/list2") ?>", 
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
                            url: "<?php echo url_for("serials/list2") ?>",
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

<script type="text/javascript">
window.click_fila_edit = function (id, e) {
    $.ajax({
        async : false,
                type: 'GET',
                url: "<?php echo url_for("serials/previewMms") ?>",
                data : {
                "id" : id
                    },
                success : function (r) {
                $('#preview').html(r);
            }
        });
    $.ajax({
        async : false,
                type: 'GET',
                dataType: 'html',
                url: "<?php echo url_for("serials/editMms") ?>",
                data : {
                "id" : id
                    },
                success : function (r) {
                $('#edit').html(r);
            }
        });
    $.ajax({
        async : false,
                type: 'GET',
                dataType: 'html',
                url: "<?php echo url_for("serials/mmsListCategories") ?>",
                data : {
                "id" : id
                    },
                success : function (r) {
                   $('.hR').html(r);
                }
        });
    $(".tv_admin_row_this").removeClass("tv_admin_row_this");
    $('#' + e).addClass("tv_admin_row_this");
}
window.click_tree_edit = function (e) {
        var busca_id = $(e).parent().attr("id").replace("node_","").trim();
        var id, title;

        $(".tv_admin_list tbody tr").each(function (index) {
                var campo10, campo11;
                $(this).children("td").each(function (index2) {
                        switch (index2) {
                        case 10:
                            campo10 = $(this).text().trim();
                            break;
                        case 11:
                            campo11 = $(this).text();
                            break;
                        }
                 })
                    if (campo10 == busca_id){
                        $(".tv_admin_row_this").removeClass("tv_admin_row_this");
                        $(this).addClass("tv_admin_row_this");
                    }
        })
}
window.delete_mms = function (id) {
    if (confirm('Seguro que desea borrar el objeto multimedia?')) {
        $.ajax({
            url:'/neweditar.php/serials/deleteMms/id/'+id,
            cache:false,
            success : function (r) {
                  $('.tv_admin_list').html(r);
                  $('#no_serial').hide();
            }
        });
    }
}
window.delete_category = function (mm_id, cat_id) {
    if (confirm('Seguro que desea borrar la cartegoria del objeto multimedia?')) {
        $.ajax({
            url: "/neweditar.php/serials/delCategory/id/" + mm_id,
                    method: "post",
                    data:{ "category" : cat_id },
                    success : function (response) {
                        for (var i=0; i<response.deleted.length; i++) {
                            var c = response.deleted[i];
                            $("#category_name_" + c.id).remove();
                            $("#category_close_" + c.id).remove();
                            $('span#'+mm_id+'_'+c.id).css('display','none');
                            //$('#node_'+c.id).children('a').text().split('-')[1].split('(')[1].split(')')[0];
                        }
                    }
            });
    }
}
window.edit_mms = function (id) {
      $.ajax({
            url:'/neweditar.php/serials/editMms/id/'+id
      });
}
window.click_fila_edit_ruben = function(element, tr, id)
{
    $('#preview_'+element).load('/neweditar.php/'+element+'s/preview/id/'+id);

    $$('.tv_admin_row_this').invoke('removeClassName', 'tv_admin_row_this');
    if (tr != null) tr.parentNode.addClassName('tv_admin_row_this');
}
$(function(){
    $('#tv_admin_container').mousemove(function(e){
            if ($(e.target).parent().hasClass('drop')){
                $('.jstree-invalid').addClass('jstree-ok');
                $('.jstree-ok').removeClass('jstree-invalid');
            } else {
                $('.jstree-ok').addClass('jstree-invalid');
                $('.jstree-invalid').removeClass('jstree-ok');
            }
        });
    $('.drop').mouseover(function(e) {
            if ($('.jstree-ok').length > 0 || $('.jstree-invalid').length > 0){
                $(e.target).parent().addClass('tv_admin_row_this');
            }
        });
    $('.drop').mouseout(function(e) {
            if ($('.jstree-ok').length > 0 || $('.jstree-invalid').length > 0){
                $(e.target).parent().removeClass('tv_admin_row_this');
            }
        });
});
</script>