<?php /*
<div style="padding: 0px 7px; background: #8eb0bc url(/images/admin/cab/cab.gif) repeat-x scroll 0% 0%; color: #DE7010; font-size: 1.5em; text-shadow: 2px 2px #ff0000;">
 <img style="width:280px; padding-left:5%" src="/images/admin/cab/pumukitDer.png">
</div>
*/ ?>

<div style="" class="jstree jstree-5 jstree-default jstree-focused" id="pumukitTree"></div>

<script class="source below" type="text/javascript">
$(function () {
  $("#pumukitTree")
  .delegate("a","click", function(e) {
    parent.main.location.href = "<?php echo url_for("index/mainpanel")?>/id/" + $(this).parent().attr("id").split("_")[1];
  })
  .jstree({ 
  // List of active plugins, RR quito: "search", "hotkeys","contextmenu", "dnd", "crrm", "cookies",
    "plugins" : ["themes","json_data","ui","types"],

  // I usually configure the plugin that handles the data first
  // This example uses JSON as it is most common
  "json_data" : { 
  // This tree is ajax enabled - as this is most common, and maybe a bit more complex
  // All the options are almost the same as jQuery's AJAX (read the docs)
      "ajax" : {
      // the URL to fetch the data
        "url" : "<?php echo url_for("index/cattree") ?>",
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
  });


});
</script>