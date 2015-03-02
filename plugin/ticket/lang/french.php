<<<<<<< HEAD
<?php /* License: see /license.txt */
//Needed in order to show the plugin title
$strings['plugin_title']        = "Tickets";
$strings['plugin_comment']      = "Plugin de gestion des tickets de support.";

$strings['TicketNum']     = "Ticket #";
$strings['Date']          = "Date";
$strings['DateLastEdition']   = "Dernière modification";
$strings['Category']      = "Catégorie";
$strings['User']          = "Utilisateur";
$strings['Program']       = "Programme";
$strings['Responsible']   = "Assigné à";
$strings['Status']        = "Statut";
$strings['Message']      = "Messages";
$strings['Description']   = "Description";

$strings['MyTickets']      = "Mes tickets";
$strings['MsgWelcome']     = "Ceci est la section MES Tickets, où vous pouvez suivre l'évolution des tickets que vous avez créé";
$strings['TckSuccessSave'] = "Votre ticket a été enregistré";

$strings['ValidUser']      = "Veuillez sélectionner un utilisateur";
$strings['ValidType']      = "Veuillez sélectionner un type";
$strings['ValidSubject']   = "Veuillez sélectionner un sujet";
$strings['ValidCourse']    = "Veuillez sélectionner un cours";
$strings['ValidEmail']     = "L'adresse e-mail doit être correcte";
$strings['ValidMessage']   = "Veuillez introduire un message";

$strings['Presential']     = "Présentiel";
$strings['PersonalEmail']  = "E-mail personnel";
$strings['Optional']       = "Optionnel";
$strings['ErrorRegisterMessage'] = "Le ticket n'a pas pu être enregistré";
$strings['Source']       = "Source";

=======
<?php
$strings['plugin_title'] = "Tickets de support";
$strings['plugin_comment'] = "Plugin de gestion des tickets de support.";
$strings['tool_enable'] = "Activer le plugin de tickets";
$strings['tool_enable_help'] = "Activer l'outil de tickets activera un nouvel onglet dans le même menu horizontal. Cet onglet apparaîtra pour tous les utilisateurs et les mènera au système de gestion de tickets où ils pourront vérifier l'état de leurs tickets.";
$strings['TabsTickets'] = "Onglet tickets";
$strings['TicketNum'] = "Ticket #";
$strings['Date'] = "Date";
$strings['Category'] = "Catégorie";
$strings['User'] = "Utilisateur";
$strings['Program'] = "Programme";
$strings['Responsible'] = "Assigné à";
$strings['Status'] = "État";
$strings['Message'] = "Messages";
$strings['Description'] = "Description";
$strings['Tickets'] = "Tickets";
$strings['MyTickets'] = "Mes tickets";
$strings['MsgWelcome'] = "Ceci est la section MES Tickets, où vous pouvez suivre l'évolution des tickets que vous avez créé";
$strings['TckSuccessSave'] = "Votre ticket a été enregistré";
$strings['TckClose'] = "Fermer le ticket";
$strings['TckNew'] = "Nouveau ticket";
$strings['TcksNew'] = "Nouveaux tickets";
$strings['Unassigned'] = "Non assignés";
$strings['Unassign'] = "Désassigné";
$strings['Read'] = "Lus";
$strings['Unread'] = "Non lus";
$strings['RegisterDate'] = "Date d'enregistrement";
$strings['AssignedTo'] = "Assigné à";
$strings['ValidUser'] = "Veuillez sélectionner un utilisateur";
$strings['ValidType'] = "Veuillez sélectionner un type";
$strings['ValidSubject'] = "Veuillez sélectionner un sujet";
$strings['ValidCourse'] = "Veuillez sélectionner un cours";
$strings['ValidEmail'] = "L'adresse e-mail doit être correcte";
$strings['ValidMessage'] = "Veuillez introduire un message";
$strings['PersonalEmail'] = "E-mail personnel";
$strings['Optional'] = "Optionnel";
$strings['ErrorRegisterMessage'] = "Le ticket n'a pas pu être enregistré";
$strings['Source'] = "Source";
$strings['DeniedAccess'] = "Accès non autorisé.";
$strings['StsNew'] = "Nouveau";
$strings['StsPending'] = "En attente";
$strings['StsUnconfirmed'] = "À confirmer";
$strings['StsClose'] = "Fermé";
$strings['StsForwarded'] = "Réenvoyé";
$strings['Priority'] = "Priorité";
$strings['PriorityHigh'] = "Haute";
$strings['PriorityNormal'] = "Normale";
$strings['PriorityLow'] = "Basse";
$strings['SrcEmail'] = "E-mail";
$strings['SrcPhone'] = "Téléphone";
$strings['SrcPresential'] = "En personne";
$strings['TicketAssignedMsg'] = "<p>Cher/Chère %s </p><p>Le <a href='%s'>ticket %s</a> vous a été assigné.</p><p>Message envoyé depuis le système de support.</p>";
$strings['TicketAssignX'] = "[TICKETS] Assignation de ticket #%s";
$strings['AreYouSureYouWantToCloseTheTicket'] = "Êtes-vous certain de vouloir fermer ce ticket?";
$strings['AreYouSureYouWantToUnassignTheTicket'] = "Êtes-vous certain de vouloir désassigner le ticket?";
$strings['YouMustWriteAMessage'] = "Vous devez introduire un message";
$strings['LastResponse'] = "Dernière réponse";
$strings['AssignTicket'] = "Assigner ticket";
$strings['AttendedBy'] = "Pris en charge par";
$strings['IfYouAreSureTheTicketWillBeClosed'] = "Si vous êtes certain, le ticket sera clôturé";
$strings['YourQuestionWasSentToTheResponableAreaX'] = "<p>Votre demande de support a été réenvoyée au responsable du département: <a href='mailto:%s'>%s</a></p>";
$strings['YourAnswerToTheQuestionWillBeSentToX'] = "<p>La réponse à votre demande de support sera envoyée à l'e-mail:<a href='#'>%s</a></p>";
$strings['VirtualSupport'] = "Support virtuel";
$strings['IncidentResentToVirtualSupport'] = "L'incident a été envoyé au support virtuel";
$strings['DateLastEdition'] = "Date de la dernière édition";
$strings['GeneralInformation'] = "Information générale";
$strings['TicketsAboutGeneralInformation'] = "Tickets liés à information générale.";
$strings['Enrollment'] = "Inscription";
$strings['TicketsAboutEnrollment'] = "Tickets liés à l'inscription.";
$strings['RequestAndPapework'] = "Questions précédentes et procédures";
$strings['TicketsAboutRequestAndPapework'] = "Tickets liés aux questions précédentes et procédures.";
$strings['AcademicIncidence'] = "Incidences académiques";
$strings['TicketsAboutAcademicIncidence'] = "Tickets liés aux incidences académiques, comme les examens, les pratiques, tâches, etc.";
$strings['VirtualCampus'] = "Campus virtuel";
$strings['TicketsAboutVirtualCampus'] = "Tickets liés au campus virtuel";
$strings['OnlineEvaluation'] = "Évaluation en ligne";
$strings['TicketsAboutOnlineEvaluation'] = "Tickets liés aux évaluations en ligne";
$strings['ToBeAssigned'] = "À assigner";
$strings['Untill'] = "Jusqu'au";
$strings['TicketWasThisAnswerSatisfying'] = "La réponse au ticket est-elle satisfaisante?";
$strings['TicketDetail'] = "Détails du ticket";
$strings['AreYouSure'] = "Êtes-vous certain?";
$strings['allow_student_add'] = "Permettre à l'étudiant de générer des tickets";
?>
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
