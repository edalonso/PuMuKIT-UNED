<?php use_helper('Object');?>

<div style="height:41px"></div>
<div id="tv_admin_container" style="padding: 4px 20px 20px; background-color: #8EB0BC;">
<fieldset id="tv_fieldset_none" class="" style="position:relative; background-color: #FFFFFF;">

<div style="text-align: right; padding: 10px 20px">
  <input type="checkbox" checked="checked" onchange="toggle_show_all();"/> Mostrar todas las categorias
</div>

<dl style="margin: 0px">


   <?php $aux = CategoryPeer::buildTreeArray(); foreach(($aux[0][CategoryPeer::TREE_ARRAY_CHILDREN]) as $node): $c = $node[CategoryPeer::TREE_ARRAY_NODE]?>
   <?php //if((!in_array($sf_user->getAttribute('user_id'), array(1, 5, 16)))&&(!$c->getDisplay())) continue
   if(!$c->getDisplay()) continue?>
   <div class="form-row">
   <dt><?php echo $c->getName()?>:</dt>
   <dd>
     <div id="category<?php echo $c->getId()?>_mms">

<div style="overflow:hidden">

<div style="float: left; height: 260px" class="category" id="all_category_<?php echo $c->getId()?>">
   <?php if(count($node[CategoryPeer::TREE_ARRAY_CHILDREN])):?>
     <ul class="category_tree" >
       <?php include_partial('list_categories', array('mm_id' => $mm->getId(), 
                                                      'parent'=> 'root', 
                                                      'block_cat' => $c->getId(),
                                                      'nodes' => $node[CategoryPeer::TREE_ARRAY_CHILDREN])) ?>
     </ul>
   <?php endif?>
</div>

<div style="height: 160px; float: left; padding: 120px 5px 0px">
  <a href="#">&#8592;</a>
  <a href="#">&#8594;</a>
</div>

<div style="width: 50%; height: 260px; border: 1px solid #bbb; float: left;" class="category" id="select_category_<?php echo $c->getId()?>">
    <ul class="category_tree" id="select_ul_category_<?php echo $c->getId()?>" >
       <?php foreach($mm->getCategorys($c) as $mmc):?>
       <li class="element" id="select_li_<?php echo $mmc->getId() ?>" >
          <span class="icon">&nbsp;</span>
          <span ondblclick="javascript:del_tree_cat(<?php echo $mmc->getId()?>, <?php echo $mm->getId() ?>);" >
          <?php echo $mmc->getCod() ?> - <?php echo $mmc->getName() ?> 
          </span>
       </li>
       <?php endforeach?>
    </ul>
</div>

</div>
     </div>
   </dd>
   </div>
   <?php endforeach?>


</dl>
</fieldset>
</div>

<?php echo javascript_tag('
function toggle_tree_cat(element, id) {
  $$(".cat_li_parent_" + id).each(function(e){e.toggleClassName("nodisplay")});
  element.parentElement.toggleClassName("expanded").toggleClassName("collapsed");
}

function toggle_show_all()
{
  // SHOW - Quito el punto a los nodos que muestran sus hijos al desocultar
  $$(".expanded.element, .collapsed.element").each(function(e){
    e.removeClassName("element");
  });

  // SHOW & HIDE - Oculto/Muestro elementos finales que no tiene objetos multimedia
  $$(".nomm.element").each(function(e){
    e.toggleClassName("nodisplayall");
  });

  // SHOW & HIDE - Oculto/Muestro elementos todos sus hijos son finales sin objetos multimedia
  $$(".nomm.expanded, .nomm.collapsed").each(function(e){
    if (e.getElementsBySelector("li.nomm").length == e.getElementsBySelector("li").length) {
      e.toggleClassName("nodisplayall");
    }
  });

  // HIDE - Pongo el punto a los nodos que se quedan sin hijos al ocultar
  $$(".nomm").each(function(e){
    p = e.parentElement.parentElement;
    if (p.getElementsBySelector("li.nodisplayall").length == p.getElementsBySelector("li").length) {
      p.addClassName("element");
    }
  });

}

function create_li_in_select(cat, block_cat_id, mm_id) {
  var $ul = $("select_ul_category_" + block_cat_id);
  var li = new Element("li", {"id": "select_li_" + cat.id, "class": "element"});
  var span1 = new Element("span", {"class": "icon"}).update("&nbsp;");
  var span2 = new Element("span", {"ondblclick": "del_tree_cat(" + cat.id +", "+ mm_id + ")"}).update(cat.cod+ " - " + cat.name);
  li.insert(span1).insert(span2);
  //Add quit logica.
  $ul.insert(li);
}

function inc_num_mm(cat_id, num)
{
  var aux = $("info_num_mm_" + cat_id);
  if (aux){
    var nn = (parseInt(aux.innerHTML) + num);
    var p = aux.parentElement.parentElement;
    if (nn == 0){
      p.addClassName("nomm");
    } else {
      p.removeClassName("nomm");
    }
    aux.innerHTML = nn;
  }
}

') ?>
