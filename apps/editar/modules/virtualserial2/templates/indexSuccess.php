<div id="tv_admin_container">

  <div class="sidebar" style="float: left">
  <div class="unesco_title">
     <div style="width: 150px;">
        <span style="padding: 0px 0px 0px 10px"><img src="/images/admin/cab/serial_ico.png">Series Virtuales</span>
     </div>
  </div>
  <div style="padding: 2px; background-color: #8eb0bc; text-align: center; font-weight: bold; border: 1px solid gray;">Categorias UNESCO</div>
    <div class="jstree" id="jstree" style="padding: 8px 10px; overflow: auto; width: 300px;">
     <?php include_component('virtualserial', 'tree') ?>
    </div>
  </div>

 <div style="margin-left: 15%; padding: 0 0 0 5px">
  <div id="tv_admin_bar" style="width: 23%; max-width: 350px;">
    <div id="preview_mm" style="padding: 1%; border: solid 1px #DDD; background: #8eb0bc url(/images/admin/cab/cab.gif) repeat-x scroll 0% 0%; overflow: hidden;">
     <?php include_component('virtualserial', 'preview') ?>
    </div>
  </div>

  <div id="tv_admin_content" style="overflow: hidden; margin-right: 15%; padding: 0 5px 0 0">
    <div id="list_mms" name="list_mms" act="/mms/list" style="overflow: hidden; min-width: 630px;">
     <?php include_component('virtualserial', 'list') ?>
    </div>

   <!-- div editar -->
    <div id="edit_mms" class="tv_admin_edit" style="float:left; background: #8eb0bc url(/images/admin/cab/cab.gif) repeat-x scroll 0% 0%; padding-top: 5px; min-width: 92%; overflow: hidden;">  
      <?php include_component('virtualserial', 'edit')?>
    </div>
  
    <div style="clear:both"></div>
   </div>
  </div>
</div>



<?php echo javascript_tag("
var update_file;
window.onload = function(){
  $$('div.sidebar').invoke('setStyle', { height: document.height + 'px' });
  Shadowbox.init({
    skipSetup:  true,
    onOpen:     function(element) {
                  if (typeof update_file == 'object') update_file.stop();
                },
    onClose:    function(element) {
                  if (typeof update_file == 'object') update_file.start();
                }
  });
};

window.click_fila_virtualserial = function(tr, id)
{
  new Ajax.Updater('edit_mms', '" . url_for('virtualserial/edit') . "', {
      asynchronous: true, 
      evalScripts: true,
      parameters: {id: id}
  });
  new Ajax.Updater('preview_mm', '" . url_for('virtualserial/preview') . "', {
      asynchronous: true, 
      evalScripts: true,
      parameters: {id: id}
  });
  $$('.tv_admin_row_this').invoke('removeClassName', 'tv_admin_row_this');
  if (tr != null) tr.parentNode.addClassName('tv_admin_row_this');
}

window.create_div_in_table = function(cat, mm_id){
  var td = $('list_unesco');
  var div = new Element('div', {'id': 'cat-' + cat.id, 'class': 'label label-success unesco_element'}).update(cat.name + ' ');
  var a1 = new Element('a', {'class': 'unesco_element_a'}).update('X');
  a1.onclick = function() {
     $('cat-'+cat.id).remove();
     del_tree_cat(cat.id, mm_id)
  };
  div.insert(a1);
  //Add quit logica.
  td.insert(div);
};

window.add_tree_cat = function (cat_id, mm_id) {
  new Ajax.Request('" . url_for('virtualserial/addCategory') . "',  {
    method: 'post',
    parameters: {category: cat_id, id: mm_id},
    asynchronous: true, 
    evalScripts: true,
    onSuccess: function(response){
        for (var i=0; i<response.responseJSON.added.length; i++) {
            var c = response.responseJSON.added[i];
            inc_num_mm(c.id, 1);
            if (c.group.length!=0 && c.group[1]!=undefined) {
               create_li_in_select(c, c.group[1], mm_id);
               create_div_in_table(c, mm_id);
            }
        }
        new Ajax.Updater('jstree', '" . url_for('virtualserial/tree') . "', {asynchronous:true, evalScripts:true});
    }
  });  
}

window.del_tree_cat = function(cat_id, mm_id) {
  console.log('del_tree_cat info_num_mm_' + cat_id);

  new Ajax.Request('" . url_for('virtualserial/delCategory') . "', {
    method: 'post',
    parameters: {category: cat_id, id: mm_id},
    asynchronous: true, 
    evalScripts: true,
    onSuccess: function(response){
        for (var i=0; i<response.responseJSON.deleted.length; i++) {
            var c = response.responseJSON.deleted[i];
            var element = $('select_li_' + c.id);
            var element2 = $('cat-'+c.id);
            if (element)  element.remove();
            if (element2)  element2.remove();
            inc_num_mm(c.id, -1);
        }
        new Ajax.Updater('jstree', '" . url_for('virtualserial/tree') . "', {asynchronous:true, evalScripts:true});
    }
  });
}

window.update_preview = function(id) {
  new Ajax.Updater('preview_mm', '" . url_for("virtualserial/preview") . "/id/' + id, {asynchronous:true, evalScripts:true});
}

//Global var to DnD
var dragSrcEl = null;
") ?>