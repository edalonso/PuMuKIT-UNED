<table cellspacing="0" class="tv_admin_list">
  <thead>
    <tr>
      <th width="1%">
        <input type="checkbox" onclick="window.click_checkbox_all('mm', this.checked)">
      </th>
      <th colspan="7" width="5%"></th>
      <?php if(sfConfig::get('app_mail_use')):?>
      <th width="2%"></th>
      <?php endif?>
      <th width="1%">Img</th>
      <th width="1%">Id</th>
      <th>T&iacute;tulo</th>
      <th width="1%">FechaRec</th>
      <th width="1%">FechaPub</th>
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
    <tr onmouseover="$(this).addClass('tv_admin_row_over');"  onmouseout="$(this).removeClass('tv_admin_row_over');" class="tv_admin_row_<?php echo $odd ?><?php if($mm['id'] == $sf_user->getAttribute('id', null, 'tv_admin/mm')) echo ' tv_admin_row_this'?>" >
      <td>
        <input id="<?php echo $mm['id']?>" class="mm_checkbox" type="checkbox">
      </td>
      <td>
        <?php echo image_tag('admin/bbuttons/'.$mm['status'].'_inline.gif', 'alt='.$mm['status'].' title=estado class=miniTag id=table_mms_status_' . $mm['id']) ?>
      </td>
      <td>
        <?php echo ($mm['announce']?'<span title="Novedad" style="color: blue">N</span>':'&nbsp;') ?><?php echo ($mm['important']?'<span title="Autonomo" style="color: blue">A</span>':'&nbsp;') ?>
      </td> 
      <td>
                       <?php //echo link_to_remote(image_tag('admin/mbuttons/edit_inline.gif', 'alt=editar title=editar class=miniTag'), array('update' => 'edit_mms', 'url' => 'mms/edit?id='.$mm['id'], 'script' => 'true')); ?>

        <!--
        <a href="#" onclick="new Effect.ScrollTo('edit_mms');">
          <?php echo image_tag('admin/mbuttons/edit_inline.gif', 'alt=editar title=editar')?>
        </a>
        -->
      </td>

      <td>
                       <?php //echo link_to_remote(image_tag('admin/mbuttons/delete_inline.gif', 'alt=borrar title=borrar class=miniTag'), array('update' => 'list_mms', 'url' => 'mms/delete?id='.$mm['id'], 'script' => 'true', 'confirm' => 'Seguro que desea borrar el objeto multimedia?', 'success' => '$("vista_previa_mm").innerHTML=""; $("edit_mms").innerHTML="" '));?>
      </td>

      <td>
                       <?php //echo link_to_remote(image_tag('admin/mbuttons/copy_inline.gif', 'alt=copiar title=copiar class=miniTag'), array('update' => 'list_mms', 'url' => 'mms/copy?id='.$mm['id'], 'script' => 'true'))?>
      </td>
      <?php if(sfConfig::get('app_mail_use')):?>
      <td>
                       <?php //echo m_link_to(image_tag('admin/mbuttons/email_inline.gif', 'alt=email title=email'), 'emails/preview?mm=' . $mm['id'], array('title' => 'Anunciar objeto multitmedia por correo'), array('width' => '925')) ?>
      </td>
      <?php endif?>
      <td>
                       <?php //echo ((($page == 1)&&( $i == 0)) ? '&nbsp;' : (link_to_remote('&#8593;', array('update' => 'list_mms', 'url' => 'mms/up?id='.$mm['id'], 'script' => 'true'))).(link_to_remote('&#8657;', array('update' => 'list_mms', 'url' => 'mms/top?id='.$mm['id'], 'script' => 'true'))))   ?>
      </td>
      <td>
                       <?php //echo ((($page == $total)&&( $i == $t-1))? '&nbsp;' : (link_to_remote('&#8595;', array('update' => 'list_mms', 'url' => 'mms/down?id='.$mm['id'], 'script' => 'true'))).(link_to_remote('&#8659;', array('update' => 'list_mms', 'url' => 'mms/bottom?id='.$mm['id'], 'script' => 'true')))) ?>
      </td>
      <td onclick="click_fila_edit(<?php echo $mm['id']?>, 'complet_tr_<?php echo $mm['id']?>', <?php echo $sf_user->getAttribute('serial') ?>)" >
                       <?php echo image_tag($mm['pic_url'], 'class=mini size=30x23')?>
      </td>
      <td onclick="click_fila_edit(<?php echo $mm['id']?>, 'complet_tr_<?php echo $mm['id']?>', <?php echo $sf_user->getAttribute('serial') ?>)" >
        <?php echo $mm['id']?>
      </td>
      <td onclick="click_fila_edit(<?php echo $mm['id']?>, 'complet_tr_<?php echo $mm['id']?>', <?php echo $sf_user->getAttribute('serial') ?>)">
                       <?php $value = $mm['title']; echo $value ? $value : '&nbsp;'  ?>
      </td>
      <td onclick="onclick=click_fila_edit(<?php echo $mm['id']?>, 'complet_tr_<?php echo $mm['id']?>', <?php echo $sf_user->getAttribute('serial') ?>)">
                       <?php echo $mm['recorddate']; ?>
      </td>
      <td onclick="click_fila_edit(<?php echo $mm['id']?>, 'complet_tr_<?php echo $mm['id']?>', <?php echo $sf_user->getAttribute('serial') ?>)" >
                       <?php echo $mm['publicdate']; ?>
      </td>
    </tr>
  <?php endfor; ?>
  </tbody>
  <tfoot>
    <tr>
      <th colspan="14">
        <div class="float-right">
                       <?php include_partial('global/pager_ajax', array('name' => 'mm', 'page' => $page, 'total' => $total)) ?> 
        </div>
        <?php echo $total_mm ?> Obj. Mm.
      </th>
    </tr>
  </tfoot>
</table>
<?php if (isset($msg_alert)) echo m_msg_alert($msg_alert) ?>