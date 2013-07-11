<?php setlocale(LC_TIME, $sf_user->getCulture().'_ES.utf-8') ?>
<fieldset id="tv_fieldset_none" class>
 <div class="form-row">
  <dt>Sesiones:</dt>
  
  <dd>
     <table><tbody>
          <?php if (($sessions = SessionPeer::getFromEvent($event->getId())) != null): ?>
              <?php foreach ($sessions as $session): ?>
              <tr>
                 <td><ul><li></li></ul></td>
		  <td><?php echo link_to_remote(image_tag('admin/mbuttons/copy_inline.gif', 'alt=copiar title=copiar la sesión al día siguiente'), array('update' => 'list_sessions', 'url' => 'events/copySession?id='.$session->getId())) ?></td>
                 <td><?php echo m_link_to(image_tag('admin/mbuttons/edit_inline.gif', 'alt=editar title=editar'), 'events/editSession?id='.$session->getId(), array('title' => 'Editar Sesión '.$session->getId()), array('width' => '800')) ?></td>
                 <td>&nbsp;</td>
                 <td><?php echo link_to_remote(image_tag('admin/mbuttons/delete_inline.gif', 'alt=borrar title=borrar'), array('update' => 'list_sessions', 'url' => 'events/deleteSession?id='.$session->getId(), 'script' => 'true', 'confirm' => '&iquest;Seguro?'))?></td>
                 <td>&nbsp;</td>
                 <td><span style="font-weight:bold;">Inicio: </span><?php echo $session->getInitDate(" %A %d/%m/%Y %H:%M") ?></td>
                 <td>&nbsp;&nbsp;</td>
                 <td><span style="font-weight:bold;">Fin: </span><?php echo $session->getEndDate(" %A %d/%m/%Y %H:%M") ?></td>
              </tr>
             <?php endforeach ?>
          <?php else: ?>
              <tr>
                 <td><ul><li></li></ul></td>
                 <td>No existen sesiones para este evento.</td>
              </tr>
          <?php endif ?>
     </tbody></table>
  </dd>
 </div>
</fieldset>