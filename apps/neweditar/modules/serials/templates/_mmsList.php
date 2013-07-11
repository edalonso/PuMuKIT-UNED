<div id="list_mms" name="list_mms" act="/mms/list">
<table cellspacing="0" class="tv_admin_list">
  <thead>
    <tr>
      <th width="1%">
        <input type="checkbox" onclick="window.click_checkbox_all('mm', this.checked)">
      </th>
      <th colspan="3" width="5%"></th>
      <th width="1%">Img</th>
      <th width="1%">Id</th>
      <th width="20%">T&iacute;tulo</th>
      <th width="5%">FechaRec</th>
      <th width="5%">FechaPub</th>
      <th width="5%">Categorias</th>
    </tr>
  </thead>
  <tbody>
  <?php if (count($mms) == 0):?>
    <tr>
      <td colspan="14">
       No existen objetos multimedia con esos valores.
      </td>
    </tr>
  <?php endif; ?>
  <?php $t = count($mms) ; for( $i=0; $i<$t; $i++): $mm = $mms[$i]; $odd = fmod($i, 2) ?>
    <tr data-id="<?php echo $cat->getId() ?>" onmouseover="$(this).addClass('tv_admin_row_over');"  onmouseout="$(this).removeClass('tv_admin_row_over');" id="complet_tr_<?php echo $mm->getId()?>" class="drop tv_admin_row_<?php echo $odd ?><?php if($i == 0) echo ' tv_admin_row_this'?>" >
      <td>
        <input id="<?php echo $mm->getId()?>" class="mm_checkbox" type="checkbox">
      </td>
      <td>
        <img src="/images/admin/bbuttons/0_inline.gif" alt="Numero de estado" title="Poner estado" class="miniTag" id="table_mms_status_<?php echo $mm->getId()?>">
      </td>
      <td>
<a href="#" onclick="edit_mms(<?php echo $mm->getId()?>); return false;"><img alt="editar" title="editar" class="miniTag" src="/images/admin/mbuttons/edit_inline.gif"></a>
      </td> 
      <td>
          <a href="#" onclick="delete_mms(<?php echo $mm->getId()?>); return false;">
             <img alt="borrar" title="borrar" class="miniTag" src="/images/admin/mbuttons/delete_inline.gif">
          </a>
      </td>
      <td onclick="click_fila_edit(<?php echo $mm->getId()?>, 'complet_tr_<?php echo $mm->getId()?>')" >
                       <?php echo image_tag($mm->getFirstUrlPic(), 'class=mini size=30x23')?>
      </td>
      <td onclick="click_fila_edit(<?php echo $mm->getId()?>, 'complet_tr_<?php echo $mm->getId()?>')" >
        <?php echo $mm->getId()?>
      </td>
      <td onclick="click_fila_edit(<?php echo $mm->getId()?>, 'complet_tr_<?php echo $mm->getId()?>')">
                       <?php $value = $mm->getTitle(); echo $value ? $value : '&nbsp;'  ?>
      </td>
      <td onclick="onclick=click_fila_edit(<?php echo $mm->getId()?>, 'complet_tr_<?php echo $mm->getId()?>')">
                       <?php echo $mm->getRecordDate(); ?>
      </td>
      <td onclick="click_fila_edit(<?php echo $mm->getId()?>, 'complet_tr_<?php echo $mm->getId()?>')" >
                       <?php echo $mm->getPublicDate(); ?>
      </td>
      <td>
        <?php foreach($mm->getCategorys($cat_raiz_unesco) as $unesco): ?>
         <span id="<?php echo $mm->getId()?>_<?php echo $unesco->getId()?>" title="<?php echo $unesco->getName()?>" style="font-size: 8px; padding: 1px 5px;margin:2px;<?php if ($unesco->getName()=='INFORMATIVOS Y CULTURALES') echo 'background-color:blue'?><?php if ($unesco->getName()=='Historia') echo 'background-color:green'?>" class="label label-success circulo"></span>
        <?php endforeach ?>
        <?php foreach($mm->getCategorys($cat_raiz_uned) as $uned): ?>
         <span id="<?php echo $mm->getId()?>_<?php echo $uned->getId()?>" title="<?php echo $uned->getName()?>" style="font-size: 8px; padding: 1px 5px;margin:2px;<?php if ($uned->getName()=='INFORMATIVOS Y CULTURALES') echo 'background-color:blue'?><?php if ($uned->getName()=='Historia') echo 'background-color:green'?>" class="label label-info circulo"></span>
        <?php endforeach ?>
      </td>
    </tr>
  <?php endfor; ?>
  </tbody>
  <tfoot>
    <tr>
      <th colspan="14">
        <div class="float-right">
            <?php include_partial('pager', array('id' => $cat->getId(), 'page' => $page, 'total' => $pages)) ?> 
        </div>
        <?php echo $total_mms ?> Obj. Mm.
      </th>
    </tr>
  </tfoot>
</table>
<?php if (isset($msg_alert)) echo m_msg_alert_jquery($msg_alert) ?>
</div>