<?php /* License: see /license.txt */
//Needed in order to show the plugin title
<<<<<<< HEAD
$strings['plugin_title']        = "TicketSupport";
$strings['plugin_comment']      = "Plugin para el soporte de Tickets de atencion sobre Chamilo.";

$strings['TicketNum']        = "Ticket #";
$strings['Date']             = "Fecha";
$strings['DateLastEdition']  = "Ultima Edici&oacute;n";
$strings['Category']      = "Categoria";
$strings['User']          = "Usuario";
$strings['Program']       = "Programa";
$strings['Responsible']   = "Responsable";
$strings['Status']        = "Estado";
$strings['Message']       = "Mensajes";
$strings['Description']   = "Descripcion";

$strings['Tickets']          = "Tickets";
$strings['MyTickets']        = "Mis Tickets";
$strings['MsgWelcome']       = "Bienvenido a su secci&oacute;n MIS TICKETS. Esta secci&oacute;n le permite revisar sus Tickets de Soporte generados en SOPORTE TECNICO";
$strings['TckSuccessSave']   = "Se registró con exito su ticket";
$strings['TckClose']         = "Cerrar Tickets";
$strings['TckNew']           = "Nuevo Ticket";
$strings['TcksNew']          = "Tickets Nuevos";
$strings['Unassigned']       = "No asignados";
$strings['Unassign']         = "Desasignado";
$strings['Read']             = "Leidos";
$strings['Unread']           = "No Leidos";
$strings['RegisterDate']     = "Fecha de Registro";
$strings['Priority']         = "Prioridad";
$strings['AssignedTo']       = "Asignado a";

$strings['ValidUser']      = "Debe seleccionar a un usuario";
$strings['ValidType']      = "Debe seleccionar un tipo";
$strings['ValidSubject']   = "Debe escribir un asunto";
$strings['ValidCourse']    = "Debe elegir un curso";
$strings['ValidEmail']     = "Debe digitar un email valido";
$strings['ValidMessage']   = "Debe escribir un mensaje";

$strings['PersonalEmail']  = "Email Personal";
$strings['Optional']       = "Opcional";
$strings['ErrorRegisterMessage'] = "No se pudo registrar su ticket";
$strings['Source']       = "Fuente";
$strings['DeniedAccess']   = "Acceso denegado.";


// Status Tickets
$strings['StsNew']         = "Nuevo";
$strings['StsPending']     = "Pendiente";
$strings['StsUnconfirmed'] = "Por Confirmar";
$strings['StsClose']       = "Cerrado";
$strings['StsReenviado']   = "Reenviado";

// Priority
$strings['Priority']         = "Prioridad";
$strings['PriorityHigh']     = "Alta";
$strings['PriorityNormal']   = "Normal";
$strings['PriorityLow']      = "Baja";

// Source
$strings['SrcEmail']         = "Email";
$strings['SrcPhone']         = "Telefono";
$strings['SrcPresential']    = "Presencial";

//

$strings['TckAssignedMsg']    = "<p>Estimado(a):</p><p> ? ? </p>
								<p>Se le ha sido asignado el ticket ? <a href=\"?\">Ticket</a></p>
							    <p>Mensaje enviado desde el sistema de ticket.</p>";
=======
$strings['plugin_title'] = "Tickets de soporte";
$strings['plugin_comment'] = "Plugin para el soporte de tickets de atención dentro de Chamilo.";
$strings['tool_enable'] = "Activar plugin de tickets";
$strings['tool_enable_help'] = "Activar la herramienta de tickets hará disponible una nueva pestaña en la barra principal horizontal. Esta pestaña aparecerá para todos los usuarios y los guiará al sistema de gestión de tickets donde podrán verificar el estado de sus tickets.";
$strings['TabsTickets'] = "Pestaña de tickets";
$strings['TicketNum'] = "Ticket #";
$strings['Date'] = "Fecha";
$strings['Category'] = "Categoría";
$strings['User'] = "Usuario";
$strings['Program'] = "Programa";
$strings['Responsible'] = "Responsable";
$strings['Status'] = "Estado";
$strings['Message'] = "Mensajes";
$strings['Description'] = "Descripcion";
$strings['Tickets'] = "Tickets";
$strings['MyTickets'] = "Mis Tickets";
$strings['MsgWelcome'] = "Bienvenido a su sección MIS TICKETS. Esta sección le permite revisar sus Tickets de Soporte generados en SOPORTE TECNICO";
$strings['TckSuccessSave'] = "Se registró con éxito su ticket";
$strings['TckClose'] = "Cerrar Tickets";
$strings['TckNew'] = "Nuevo Ticket";
$strings['TcksNew'] = "Tickets Nuevos";
$strings['Unassigned'] = "No asignados";
$strings['Unassign'] = "Desasignado";
$strings['Read'] = "Leídos";
$strings['Unread'] = "No Leídos";
$strings['RegisterDate'] = "Fecha de Registro";
$strings['AssignedTo'] = "Asignado a";
$strings['ValidUser'] = "Debe seleccionar a un usuario";
$strings['ValidType'] = "Debe seleccionar un tipo";
$strings['ValidSubject'] = "Debe escribir un asunto";
$strings['ValidCourse'] = "Debe elegir un curso";
$strings['ValidEmail'] = "Debe digitar un email valido";
$strings['ValidMessage'] = "Debe escribir un mensaje";
$strings['PersonalEmail'] = "Email Personal";
$strings['Optional'] = "Opcional";
$strings['ErrorRegisterMessage'] = "No se pudo registrar su ticket";
$strings['Source'] = "Fuente";
$strings['DeniedAccess'] = "Acceso denegado.";
// Status Tickets
$strings['StsNew'] = "Nuevo";
$strings['StsPending'] = "Pendiente";
$strings['StsUnconfirmed'] = "Por Confirmar";
$strings['StsClose'] = "Cerrado";
$strings['StsForwarded'] = "Reenviado";
// Priority
$strings['Priority'] = "Prioridad";
$strings['PriorityHigh'] = "Alta";
$strings['PriorityNormal'] = "Normal";
$strings['PriorityLow'] = "Baja";
// Source
$strings['SrcEmail'] = "Email";
$strings['SrcPhone'] = "Teléfono";
$strings['SrcPresential'] = "Presencial";
$strings['TicketAssignedMsg']    = "<p>Estimado(a) %s </p><p>Se le ha sido asignado el <a href=\"%s\">ticket %s</a></p><p>Mensaje enviado desde el sistema de ticket.</p>";
$strings['TicketAssignX'] = "[TICKETS] Asignación de Ticket #%s ";
$strings['AreYouSureYouWantToCloseTheTicket'] = "¿Está seguro que quiere cerrar el ticket?";
$strings['AreYouSureYouWantToUnassignTheTicket'] = "¿Está seguro que quiere desasignarse el ticket?";
$strings['YouMustWriteAMessage'] = "Debe escribir un mensaje";
$strings['LastResponse'] = "Última Respuesta";
$strings['AssignTicket'] = "Asignar Ticket";
$strings['AttendedBy'] = "Atendido por";
$strings['IfYouAreSureTheTicketWillBeClosed'] = "Si está seguro el Ticket será cerrado";
$strings['YourQuestionWasSentToTheResponableAreaX'] = "<p>Su consulta fue reenviada al área responsable: <a href='mailto: %s'>%s</a></p>";
$strings['YourAnswerToTheQuestionWillBeSentToX'] = "<p>La respuesta a su consulta será enviada al correo:<a href='#'>%s</a></p>";
$strings['VirtualSupport'] = "Soporte Virtual";
$strings['IncidentResentToVirtualSupport'] = "El incidente ha sido reenviado al Soporte Virtual";
$strings['DateLastEdition'] = "Fecha Última Edición";
$strings['GeneralInformation'] = "Información General";
$strings['TicketsAboutGeneralInformation'] = "Tickets acerca de Infomación General.";
$strings['Enrollment'] = "Matrícula";
$strings['TicketsAboutEnrollment'] = "Tickets relacionados con la Matrícula.";
$strings['RequestAndPapework'] = "Consultas y Trámites";
$strings['TicketsAboutRequestAndPapework'] = "Tickets relacionados a consultas anteriores y trámites.";
$strings['AcademicIncidence'] = "Incidencias Académicas";
$strings['TicketsAboutAcademicIncidence'] = "Tickets relacionados a incidencias académicas como exámenes, prácticas, tareas, etc.";
$strings['VirtualCampus'] = "Campus Virtual";
$strings['TicketsAboutVirtualCampus'] = "Tickets relacionados al Campus Virtual";
$strings['OnlineEvaluation'] = "Evaluación en línea";
$strings['TicketsAboutOnlineEvaluation'] = "Tickets relacionados a las evaluaciones en línea";
$strings['ToBeAssigned'] = "Por Asignar";
$strings['Untill'] = "Hasta";
$strings['TicketWasThisAnswerSatisfying'] = "¿Fué la respuesta al Ticket satisfactoria?";
$strings['TicketDetail'] = "Detalle del Ticket";
$strings['AreYouSure'] = "¿Está seguro?";

$strings['allow_student_add'] = "Permitir al studiante generar Tickets";
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
