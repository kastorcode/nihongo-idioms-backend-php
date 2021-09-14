<?php

require_once 'config.php';

use views\MainView;
use models\Forum;
use models\Modules;
use models\Notifications;
use models\Premium;
use models\Settings;
use models\Shadowing;
use models\Support;
use models\User;
use models\UserMain;
use models\Vocabulary;
use views\ChatView;
use views\ForumView;
use views\HomeView;
use views\LoginView;
use views\ModulesView;
use views\NotificationsView;
use views\PremiumView;
use views\SettingsView;
use views\ShadowingView;
use views\SupportView;
use views\UserMainView;
use views\VocabularyView;

$get['ajuda'] = 'simpleIndex';
$get['entrar'] = 'login';
$get['help'] = 'simpleIndex';
//$get['chat'] = 'index';
$get['forum'] = 'index';
$get['modules'] = 'index';
$get['modulos'] = 'index';
$get['notifications'] = 'index';
$get['notificacoes'] = 'index';
$get['settings'] = 'index';
$get['configuracoes'] = 'index';
$get['premium'] = 'index';
$get['shadowing'] = 'index';
//$get['support'] = 'index';
//$get['suporte'] = 'index';
//$get['talk'] = 'index';
$get['tutorial'] = 'simpleIndex';
$get['vocabulary'] = 'index';
$get['vocabulario'] = 'index';

$post['adBlock'] = 'adBlock';
$post['addMessage'] = 'addMessage';
$post['ajuda'] = 'tutorial';
//$post['chat'] = 'chat';
$post['clearSupport'] = 'clearSupport';
$post['forum'] = 'forum';
$post['getMessages'] = 'getMessages';
$post['help'] = 'tutorial';
$post['home'] = 'userMain';
$post['modules'] = 'modules';
$post['modulos'] = 'modules';
$post['myPhrases'] = 'myPhrases';
$post['notifications'] = 'notifications';
$post['notificacoes'] = 'notifications';
$post['settings'] = 'settings';
$post['configuracoes'] = 'settings';
$post['premium'] = 'premium';
$post['private'] = 'changePrivacy';
$post['renderSearchPhrases'] = 'renderSearchPhrases';
$post['shadowing'] = 'shadowing';
//$post['support'] = 'support';
//$post['suporte'] = 'support';
//$post['talk'] = 'chat';
$post['tutorial'] = 'tutorial';
$post['vocabulary'] = 'vocabulary';
$post['vocabulario'] = 'vocabulary';

$get['console'] = 'console';

function console() {
	echo('<pre>');
	echo session_save_path();
	var_dump('2020-7-10' == null);
	echo('</pre>');
	die();
}

function index() {
	$user = new User();
	$homeView = new HomeView();
	if ($user->logged()) $homeView->renderBase();
	else $homeView->renderHome();
}

function simpleIndex() {
	$mainView = new MainView();
	$mainView->renderSimpleBase();
}

function renderIndex() {
	$user = new User();
	$mainView = new MainView();
	if ($user->logged()) $mainView->renderBase();
	else $mainView->renderSimpleBase();
}

function adBlock() {
	$mainView = new MainView();
	$mainView->renderAdBlock();
}

function addMessage() {
	$support = new Support();
	$support->addMessage();
}

function chat() {
	$user = new User();
	if (!$user->logged()) redirect();
	$chatView = new ChatView();
	$chatView->render();
}

function changePrivacy() {
	$settings = new Settings();
	$settings->changePrivacy();
}

function clearSupport() {
	$support = new Support();
	$support->clearSupport();
}

function forum() {
	$user = new User();
	if (!$user->logged()) redirect();
	$forumView = new ForumView();
	$forumView->render();
}

function getMessages() {
	$support = new Support();
	$support->getMessages();
}

function login() {
	$user = new User();
	if ($user->logged()) redirect();
	$loginView = new LoginView();
	$loginView->setTitle('Entrar - Nihongo Idiomas');
	$loginView->render();
}

function modules() {
	$user = new User();
	if (!$user->logged()) redirect();
	$modulesView = new ModulesView();
	$modulesView->render();
}

function myPhrases() {
	$user = new User();
	if (!$user->logged()) redirect();
	$vocabularyView = new VocabularyView();
	$vocabularyView->renderMyPhrases();
}

function notifications() {
	$user = new User();
	if (!$user->logged()) redirect();
	$notificationsView = new NotificationsView();
	$notificationsView->render();
}

function premium() {
	$settingsView = new SettingsView();
	$settingsView->renderPremium();
}

function redirect($path = PATH) {
	echo "<script>location.href='$path'</script>";
	die();
}

function renderSearchPhrases() {
	$vocabularyView = new VocabularyView();
	$vocabularyView->renderSearchPhrases();
}

function settings() {
	$user = new User();
	if (!$user->logged()) redirect();
	$settingsView = new SettingsView();
	$settingsView->render();
}

function shadowing() {
	$user = new User();
	if (!$user->logged()) redirect();
	$shadowingView = new ShadowingView();
	$shadowingView->render();
}

function support() {
	$user = new User();
	if (!$user->logged()) redirect();
	$supportView = new SupportView();
	$supportView->render();
}

function tutorial() {
	$supportView = new SupportView();
	$supportView->renderTutorial();
}

function userMain() {
	$user = new User();
	if (!$user->logged()) redirect();
	$userMainView = new UserMainView();
	$userMainView->render();
}

function vocabulary() {
	$user = new User();
	if (!$user->logged()) redirect();
	$vocabularyView = new VocabularyView();
	$vocabularyView->render();
}

// Forum Controller - Start

$get['addQuestion'] = 'addQuestion';
$get['addReply'] = 'addReply';
$get['deleteQuestion'] = 'deleteQuestion';
$get['deleteReply'] = 'deleteReply';
$get['getMyQuestions'] = 'getMyQuestions';
$get['getQuestion'] = 'getQuestion';
$get['getQuestions'] = 'getQuestions';
$get['getReplies'] = 'getReplies';
$get['searchQuestions'] = 'searchQuestions';

function addQuestion() {
	$forum = new Forum();
	$forum->addQuestion();
}

function addReply() {
	$forum = new Forum();
	$forum->addReply();
}

function deleteQuestion() {
	$forum = new Forum();
	$forum->deleteQuestion();
}

function deleteReply() {
	$forum = new Forum();
	$forum->deleteReply();
}

function getMyQuestions() {
	$forum = new Forum();
	$forum->getMyQuestions();
}

function getQuestion() {
	$forum = new Forum();
	$forum->getQuestion();
}

function getQuestions() {
	$forum = new Forum();
	$forum->getQuestions();
}

function getReplies() {
	$forum = new Forum();
	$forum->getReplies();
}

function searchQuestions() {
	$forum = new Forum();
	$forum->searchQuestions();
}

// Forum Controller - End

// Home Controller - Start

$get['home'] = 'index';
$get['index'] = 'index';
$get['enter'] = 'enter';
$get['logout'] = 'logout';

function enter() {
	$user = new User();
	$user->login();
}

function logout() {
	$user = new User();
	$user->logout();
}

// Home Controller - End

// Notifications Controller - Start

$get['checkNotifications'] = 'checkNotifications';
$get['clearNotifications'] = 'clearNotifications';
$get['getNotification'] = 'getNotification';
$get['getNotifications'] = 'getNotifications';

function checkNotifications() {
	$notifications = new Notifications();
	$notifications->checkNotifications();
}

function clearNotifications() {
	$notifications = new Notifications();
	$notifications->clearNotifications();
}

function getNotification() {
	$notifications = new Notifications();
	$notifications->getNotification();
}

function getNotifications() {
	$notifications = new Notifications();
	$notifications->getNotifications();
}

// Notifications Controller - End

// Modules Controller - Start

$get['getModule'] = 'getModule';
$get['getModules'] = 'getModules';

function getModule() {
	$modules = new Modules();
	$modules->getModule();
}

function getModules() {
	$modules = new Modules();
	$modules->getModules();
}

// Modules Controller - End

// Premium Controller - Start

$get['checkPremium'] = 'checkPremium';
$get['getBuyLink'] = 'getBuyLink';
$get['getMinimumPrice'] = 'getMinimumPrice';
$get['getPremium'] = 'getPremium';
$post['mercadopagoipn'] = 'mercadoPagoIPN';

function checkPremium() {
	$premium = new Premium();
	$premium->checkPremium();
}

function getBuyLink() {
	$premium = new Premium();
	$premium->getBuyLink();
}

function getMinimumPrice() {
	$premium = new Premium();
	$premium->getMinimumPrice();
}

function getPremium() {
	$premium = new Premium();
	$premium->getPremium();
}

function mercadoPagoIPN() {
	$premium = new Premium();
	$premium->mercadoPagoIPN();
}

// Premium Controller - End

// Settings Controller - Start

$get['addCourse'] = 'addCourse';
$get['changeAds'] = 'changeAds';
$get['changeAuto'] = 'automaticAudio';
$get['changeGender'] = 'changeGender';
$get['changeName'] = 'changeName';
$get['changeRepro'] = 'changeAudioPlayback';
$get['changeTheme'] = 'changeTheme';
$get['getCourses'] = 'getCourses';

function addCourse() {
	$settings = new Settings();
	$settings->addCourse();
}

function automaticAudio() {
	$settings = new Settings();
	$settings->automaticAudio();
}

function changeAds() {
	$settings = new Settings();
	$settings->changeAds();
}

function changeGender() {
	$settings = new Settings();
	$settings->changeGender();
}

function changeName() {
	$settings = new Settings();
	$settings->changeName();
}

function changeAudioPlayback() {
	$settings = new Settings();
	$settings->changeAudioPlayback();
}

function changeTheme() {
	$settings = new Settings();
	$settings->changeTheme();
}

function getCourses() {
	$settings = new Settings();
	$settings->getCourses();
}

// Settings Controller - End

// Shadowing Controller - Start

$get['getShadowing'] = 'getShadowing';
$get['getTexts'] = 'getTexts';

function getShadowing() {
	$shadowing = new Shadowing();
	$shadowing->getShadowing();
}

function getTexts() {
	$shadowing = new Shadowing();
	$shadowing->getTexts();
}

// Shadowing Controller - End

// Support Controller - Start

$get['getTutorial'] = 'getTutorial';

function getTutorial() {
	$support = new Support();
	$support->getTutorial();
}

// Support Controller - End

// UserMain Controller - Start

$get['changeCourse'] = 'changeCourse';
$get['getMainAd'] = 'getMainAd';
$get['mainAdClick'] = 'mainAdClick';

function changeCourse() {
	$settings = new Settings();
	$settings->changeCourse();
}

function getMainAd() {
	$userMain = new UserMain();
	$userMain->getMainAd();
}

function mainAdClick() {
	$userMain = new UserMain();
	$userMain->mainAdClick();
}

// UserMain Controller - End

// Vocabulary Controller - Start

$get['addExistingPhrase'] = 'addExistingPhrase';
$get['addPhrase'] = 'addPhrase';
$get['deletePhrase'] = 'deletePhrase';
$get['finishSyncDB'] = 'finishSyncDB';
$get['getLearnedPhrases'] = 'getLearnedPhrases';
$get['getPhrases'] = 'getPhrases';
$get['getRevisions'] = 'getRevisions';
$get['getTotalLearned'] = 'getTotalLearned';
$get['getTotalPhrases'] = 'getTotalPhrases';
$get['phrase'] = 'phrase';
$get['phraseAndTranslation'] = 'phraseAndTranslation';
$get['searchMyPhrases'] = 'searchMyPhrases';
$get['searchPhrases'] = 'searchPhrases';
$get['syncAll'] = 'syncAll';
$get['syncDB'] = 'syncDB';
$get['train'] = 'train';

function addExistingPhrase() {
	$vocabulary = new Vocabulary();
	$vocabulary->addExistingPhrase();
}

function addPhrase() {
	$vocabulary = new Vocabulary();
	$vocabulary->addPhrase();
}

function deletePhrase() {
	$vocabulary = new Vocabulary();
	$vocabulary->deletePhrase();
}

function finishSyncDB() {
	$vocabulary = new Vocabulary();
	$vocabulary->finishSyncDB();
}

function getLearnedPhrases() {
	$vocabulary = new Vocabulary();
	$vocabulary->getLearnedPhrases();
}

function getPhrases() {
	$vocabulary = new Vocabulary();
	$vocabulary->getPhrases();
}

function getRevisions() {
	$vocabulary = new Vocabulary();
	$vocabulary->getRevisions();
}

function getTotalPhrases() {
	$vocabulary = new Vocabulary();
	$vocabulary->getTotalPhrases();
}

function getTotalLearned() {
	$vocabulary = new Vocabulary();
	$vocabulary->getTotalLearned();
}

function phrase() {
	$vocabulary = new Vocabulary();
	$vocabulary->phrase();
}

function phraseAndTranslation() {
	$vocabulary = new Vocabulary();
	$vocabulary->phraseAndTranslation();
}

function searchMyPhrases() {
	$vocabulary = new Vocabulary();
	$vocabulary->searchMyPhrases();
}

function searchPhrases() {
	$vocabulary = new Vocabulary();
	$vocabulary->searchPhrases();
}

function syncAll() {
	$vocabulary = new Vocabulary();
	$vocabulary->syncAll();
}

function syncDB() {
	$vocabulary = new Vocabulary();
	$vocabulary->syncDB();
}

function train() {
	$vocabulary = new Vocabulary();
	$vocabulary->train();
}

// Vocabulary Controller - End

?>