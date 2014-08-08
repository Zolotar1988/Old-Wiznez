<?php
/**
 * Template Name: KANBAN
 *
 * @package WordPress
 * @subpackage KANBAN
 * @since WP 1.0
 */

require_once __DIR__. '/../classes/class.kanban.init.php';

$kanbanProvider = new KanbanInit();
get_header();

$kanbanProvider->add_kanabn_ie9_js();
?>

<div id="main-content" class="main-content">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
            <header class="entry-header"><h1 class="entry-title"><?=the_title();?></h1></header>


            <!-- Add your site or application content here -->
            <header class="navbar navbar-fixed-top" role="navigation" id="headerMenu">
                <div class="navbar-inner">
                    <div class="container">
                        <div class="navbar-header col-md-3">
                            <span id="kanbanName" class="navbar-brand" ng-model="kanban" ng-hide="editingName"><a href="#renameKanban" class="renameKanban" ng-click="editingKanbanName()">{{kanban.name}}</a></span>
                            <div ng-show="editingName" class="pull-left">
                                <form ng-submit="rename()">
                                    <div class="input-group">
                                          <span class="input-group-addon">
                                            <a href="#cancel" ng-click="editingName=false"><i class="glyphicon glyphicon-tasks"></i></a>
                                          </span>
                                        <input type="text" name="kanbanName" ng-model="newName" class="form-control">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <ul class="nav navbar-nav pull-right" id="menu" ng-controller="MenuController">
                                <li class="dropdown">
                                    <a href="#kanbanMenu" class="dropdown-toggle" data-toggle="dropdown">Kanban <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#newKanban" class="mpkNew" title="New Kanban board" ng-click="newKanban()"><i class="glyphicon glyphicon-briefcase"></i> New</a>
                                        </li>
                                        <li><a href="#open" title="Open saved Kanban board" ng-click="openKanban()"><i class="glyphicon glyphicon-folder-open"></i> Open <small class="shortcut pull-right">ctrl-shift-o</small></a>
                                        </li>
                                        <li><a href="#delete" title="Downloadelete Kanban from local storage" ng-click="delete()"><i class="glyphicon glyphicon-remove-sign"></i> Delete</a></li>
                                        <li><a href="#theme" title="Select Theme for the Kanban board" ng-click="selectTheme()"><i class="glyphicon glyphicon-picture"></i> Themes</a></li>
                                        <li role="presentation" class="divider"></li>
                                        <li><a href="#help" title="Help" ng-click="help()"><i class="glyphicon glyphicon-question-sign"></i> Help <small class="shortcut pull-right">ctrl-shift-h</small></a></li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="nav navbar-nav pull-right" id="cloudMenu" ng-controller="CloudMenuController">
                                <li class="dropdown">
                                    <a href="#cloudMenu" class="dropdown-toggle" data-toggle="dropdown">Cloud <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#cloudSetup" ng-click="openCloudSetup()"><i class="glyphicon glyphicon-wrench"></i> Setup</a></li>
                                        <li><a href="#upload" ng-click="upload()"><i class="glyphicon glyphicon-upload"></i> Upload</a></li>
                                        <li><a href="#download" ng-click="download()"><i class="glyphicon glyphicon-download"></i> Download</a></li>
                                        <li><a href="https://my-personal-kanban.appspot.com/" target="blank"><i class="glyphicon glyphicon-hand-right"></i> Go to Cloud</a></li>
                                    </ul>
                                </li>
                            </ul>
                            <div id="info" class="nav pull-right" ng-show="showInfo">
                                <span id="error" class="error" ng-show="showError"><a href="#close" ng-click="showInfo=false;showError=false;errorMessage=''">{{errorMessage}}</a></span>
                                <span id="message" class="">{{infoMessage}}</span>
                                <span id="spinner" class="pull-right" spin="spinConfig" spin-if="showSpinner"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="container-fluid" id="kanban" ng-controller="KanbanController">
            <div ng-model="kanban">
                <div id="columns" class="row">
                    <div class="col-md-{{12/kanban.numberOfColumns}}" ng-repeat="column in kanban.columns" data-columnindex="{{$index}}" id="column{{$index}}">
                        <div class="column">
                            <div class="columnHeader">
                                <a href="#addCard" title="Add card to column" class="pull-right" ng-click="addNewCard(column)"><i class="glyphicon glyphicon-plus"></i></a>
                                <a href="#changeColumnName" title="Change column name" ng-click="editing = true" ng-model="column" ng-hide="editing"><i class="glyphicon glyphicon-tasks"></i></a>
                                <span ng-hide="editing">{{column.name}} ({{column.cards.length}})</span>
                                <form ng-show="editing" ng-submit="editing = false">
                                    <div class="input-group">
                                        <span class="input-group-addon"><a href="#cancel" ng-click="editing = false"><i class="glyphicon glyphicon-tasks"></i></a></span>
                                        <input class="form-control" type="text" ng-model="column.name" value="{{column.name}}" required="" focus-me="editing">
                                    </div>
                                </form>
                            </div>
                            <ul class="cards" ui-sortable="{connectWith: '#kanban ul.cards'}" sortable="" ng-model="column.cards" style="{{minHeightOfColumn}}">
                                <li class="card" ng-repeat="card in column.cards" style="background-color: #{{colorFor(card)}}">
                                    <a href="#deleteCard" class="pull-right" ng-click="delete(card, column)"><i class="glyphicon glyphicon-remove"></i></a>
                                    <a ng-click="openCardDetails(card)"><span tooltip-popup-delay="2000" tooltip="{{details(card)}}">{{card.name}}</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <section id="kanbanOperations">
            <!-- this one is for the new card -->
            <script type="text/ng-template" id="NewKanbanModal.html">
                <form class="noMargin" ng-submit="createNew('#newKanban')" name="newKanbanForm">
                    <div class="modal-header">
                        <button type="button" class="close" ng-click="closeNewKanban()">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">New Kanban board</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label" for="kanbanNameFormField">Kanban name</label>
                            <div>
                                <input type="text" id="kanbanNameFormField" placeholder="Kanban name" class="form-control" ng-model="kanbanName" required focus-me />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="numberOfColumnsField">Number of columns</label>
                            <div>
                                <select id="numberOfColumnsField" ng-model="numberOfColumns" class="form-control">
                                    <option>2</option>
                                    <option selected="selected">3</option>
                                    <option>4</option>
                                    <option>6</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" ng-click="closeNewKanban()">Close</button>
                        <button type="submit" class="btn btn-primary" >Create new</button>
                    </div>
                </form>
            </script>

            <script type="text/ng-template" id="OpenKanban.html">
                <form ng-submit="open()" name="openKanbanForm" class="noMargin">
                    <div class="modal-header">
                        <button type="button" class="close" ng-click="close()">&times;</button>
                        <h4 class="modal-title" id="openKanbanLabel">Open another Kanban</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label" for="kanbanNameFormField">Kanban name</label>
                            <div>
                                <select name="selectedToOpen" ng-model="selectedToOpen" class="form-control" required ng-options="name for name in allKanbans" focus-me="selectedToOpen">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" type="button" ng-click="close()">Close</button>
                        <button class="btn btn-primary" type="submit">Open</button>
                    </div>
                </form>
            </script>
            <script type="text/ng-template" id="NewKanbanCard.html">
                <form ng-submit="addNewCard()" class="noMargin" name="newCardForm">
                    <div class="modal-header">
                        <button type="button" class="close" ng-click="close()">&times;</button>
                        <h4 class="modal-title" ng-model="kanbanColumnName">New card for column '{{kanbanColumnName}}'</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label" for="newCardTitle">Kanban card title</label>
                            <div>
                                <input type="text" id="newCardTitle" placeholder="Title on a card" ng-model="title" required focus-me class="cardInputs form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="newCardDetails">More details <small>(optional)</small></label>
                            <div>
                                <textarea id="newCardDetails" ng-model="details" class="cardInputs form-control" rows="7" >
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Card color</label>
                        </div>
                        <div class="form-group">
                            <color-selector options="colorOptions" ng-model="cardColor" prefix="newCardColor" class="colorSelector" show-hex-code="true"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" ng-click="close()">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </script>
            <script type="text/ng-template" id="OpenCard.html">
                <form ng-submit="update()" class="noMargin" name="cardDetails">
                    <div class="modal-header">
                        <button type="button" class="close" ng-click="close()">&times;</button>
                        <h4 class="modal-title">Card details</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label" for="cardTitle">Kanban card title</label>
                            <div class="controls">
                                <ng-form ng-submit="editingTitle = false">
                                    <div>
                                        <input name="cardTitle" type="text" id="cardTitle" placeholder="Title on a card" ng-model="name" required class="cardInputs" ng-disabled="!editingTitle" focus-me />
                                        <a href="#editTitle" title="Edit card title" ng-click="editingTitle = true" ng-hide="editingTitle" class="btn pull-right"><i class="glyphicon glyphicon-pencil"></i></a>
                                        <a href="#editTitle" title="OK" ng-click="editingTitle = false" ng-hide="!editingTitle" class="btn pull-right"><i class="glyphicon glyphicon-ok"></i></a>
                                </ng-form>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="cardTitle">Details</label>

                        <div class="controls clearfix">
                            <textarea id="details" ng-model="details" class="cardInputs" rows="7" ng-show="editDetails">
                            </textarea>
                            <div class="cardDetails cardInputs pull-left" ng-show="!editDetails" ng-bind-html="details|linky|cardDetails"></div>

                            <a href="#editDetails" title="Edit card title" ng-click="editDetails = true" ng-hide="editDetails" class="btn pull-right"><i class="glyphicon glyphicon-pencil"></i></a>
                            <a href="#editDetails" title="OK" ng-click="editDetails = false" ng-hide="!editDetails" class="btn pull-right"><i class="glyphicon glyphicon-ok"></i></a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Card color</label>
                    </div>
                    <div class="form-group">
                        <color-selector options="colorOptions" ng-model="cardColor" prefix="editCardColor" class="colorSelector" show-hex-code="true" />
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" ng-click="close()">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </script>

            <script type="text/ng-template" id="SelectTheme.html">
                <form ng-submit="switchTheme()" name="selectTheme" class="noMargin">
                    <div class="modal-header">
                        <button type="button" class="close" ng-click="close()">&times;</button>
                        <h4 class="modal-title" id="openKanbanLabel">Choose Kanban Theme</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label" for="kanbanTheme">Themes to choose from</label>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <select class="form-control" name="selectedToOpen" ng-model="model.selectedTheme" required ng-options="t.css as t.name for t in model.themes" id="kanbanTheme">
                                </select>
                            </div>
                            <div class="col-md-5">
                                <img src="img/themes/{{model.selectedTheme}}.jpg" width="250" class="thumbnail" style="border: 1px solid black"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" type="button" ng-click="close()">Close</button>
                        <button class="btn btn-primary" type="submit" ng-click="switchTheme()">Switch</button>
                    </div>
                </form>
            </script>

            <script type="text/ng-template" id="SetupCloudModal.html">
                <form ng-submit="saveSettings()" name="cloudSettings" class="noMargin">
                    <div class="modal-header">
                        <button type="button" class="close" ng-click="close()">&times;</button>
                        <h4 class="modal-title">Cloud Setup</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" ng-show="model.showConfigurationError">
                            <p>In order to use Cloud Upload and Download functionality, you need to generate Kanban Key, a unique identifier that will be used to upload and download your Kanban. You can to that at My Personal Kanban web service <a href="http://my-personal-kanban.appspot.com" target="blank">http://my-personal-kanban.appspot.com</a></p>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="kanbanKey">Kanban key:</label>
                            <div>
                                <input type="text" name="kanbanKey" id="kanbanKey" ng-model="model.kanbanKey" required class="kanbanKey form-control" placeholder="Cloud Kanban key" valid-key />
                                <span class="text-danger" ng-show="cloudSettings.kanbanKey.$error.validKey">Provided key is invalid</span>
                                <span class="text-danger" ng-show="cloudSettings.kanbanKey.$error.validKeyUnableToVerify">Unable to validate key. You might not be connected to the Internet or Network or unable to access <a href="http://my-personal-kanban.appspot.com" target="blank" ng-hide="model.useLocalCloud">http://my-personal-kanban.appspot.com</a> <a href="model.localCloudUrl">{{model.localCloudUrl}}</a></span>

                            </div>
                        </div>
                        <div class="alert alert-info" ng-hide="model.showConfigurationError || model.useLocalCloud">
                            <p>If you need to retrieve your kanban key or generate a new one go to <a href="http://my-personal-kanban.appspot.com" target="blank">http://my-personal-kanban.appspot.com</a></p>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="cloudEncryptionKey">Cloud encryption key:</label>
                            <div>
                                <input type="text" name="cloudEncryptionKey" id="cloudEncryptionKey" class="kanbanKey form-control" ng-model="model.encryptionKey"/>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <p>This key will be used to Encrypt your Kanban when uploading into Cloud and Decrypt when downloading. It can be any number of characters.</p>
                            <p><small>If you make changes to this key, make sure to download latest Kanban from Cloud first and upload after.</small></p>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Local Cloud:</label>
                            <div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" ng-model="model.useLocalCloud"> Use own Local Cloud
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-show="model.useLocalCloud">
                            <label class="control-label" for="localCloudUrl">Local Cloud URL:</label>
                            <div>
                                <input type="text" name="localCloudUrl" id="localCloudUrl" class="form-control" ng-model="model.localCloudUrl" required />
                            </div>
                        </div>
                        <div class="alert alert-info" ng-show="model.useLocalCloud">
                            <p><small>You need to specify a valid URL for your Local Cloud service. Please provide hostname with port only (eg. https://my-personal-kanban.appspot.com). If the URL is valid your Kanban key should validate when you save Cloud settings.</small></p>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-default" type="button" ng-click="close()">Close</button>
                        <button class="btn btn-primary" type="submit" ng-disabled="cloudSettings.kanbanKey.$error.validKey">Save settings</button>
                    </div>

                </form>
            </script>

            <script type="text/ng-template" id="HelpModal.html">
                <div class="modal-header">
                    <button type="button" class="close" ng-click="close()">&times;</button>
                    <h4 class="modal-title">Help</h4>
                </div>
                <div class="modal-body">
                    <p><span class="version">My Personal Kanban Version: 0.5.0</span> from <a href="http://greggigon.github.io/my-personal-kanban/" target="blank">http://greggigon.github.io/my-personal-kanban/</a></p>
                    <p>
                        <strong>Personal Kanban</strong><br />
                        <small>You can create <strong>new Kanban</strong> by selecting <strong>Kanban -> New</strong> from the Kanban menu. You can give it a name and select number of columns.<br/>
                            Once Kanban is created you can <strong>rename</strong> it by <strong>clicking it's title</strong> in the top bar. You can add new cards to the columns by pressing <strong>&plus;</strong> in the top right of the column. <br />
                            You can give each <strong>column name</strong> by clicking on icon next to it's name. <strong>(Number)</strong> next to column name indicates number of cards in the column.<br /><br />
                            <strong>Cards are created</strong> with <strong>title</strong> and possible longer <strong>description</strong>. You can also <strong>select different colour</strong> for the card if you would like to categorize them somehow. Description will <strong>keep the new line formating</strong> and add <strong>clickable links</strong> if you include any. <br />
                            You can <strong>open Card details</strong> by clicking on <strong>card title</strong>.You can <strong>edit Card details</strong> once opened.<br />
                            You can <strong>drag card between columns</strong> and move them <strong>up and down</strong> column list.<br /><br />
                            You can have <strong>multiple Kanbans</strong> and open/switch them from the <strong>Kanban -> Open</strong> menu or by <strong>pressing <abbr title="Ctrl-Shift-o when browser window is selected">Ctrl-Shift-o</abbr></strong> on keyboard. <br />
                            You can <strong>select different style</strong> of Kanban board from <strong>Kanban -> Theme</strong> menu.<br />
                            You can <strong>delete</strong> entire Kanban by selecting <strong>Kanban -> Delete</strong> from the menu.<br />
                            Your Kanbans are automatically <strong>saved</strong> in your <strong>browser storage</strong>.
                        </small>
                    </p>
                    <p>
                        <strong>Cloud</strong><br />
                        <small>
                            You can use <strong>My Personal Kanban Cloud</strong> service that enables <strong>Upload and Download</strong> from Cloud. This service is offered free of charge. <br />
                            You will need to create your <strong>cloud Kanban key</strong>. Follow instructions on <strong>Cloud -> Setup</strong>.<br />
                            Your Kanban in the Cloud <strong>is stored Encrypted</strong>, that's why you should setup <strong>Encryption Key</strong>. The Encryption algorithm is <a href="http://en.wikipedia.org/wiki/Rabbit_(cipher)" target="blank" title="Rabbit encryption algorithm">Rabbit</a>.<br />
                            You can <strong>upload Kanban</strong> by selecting <strong>Cloud -> Upload</strong> from the menu <br />.
                            You can <strong>download Kanban</strong> by selecting <strong>Cloud -> Download</strong> from the menu. <br /><br />
                            You can also use your own Local Cloud. There is server implementation provided at: <a href="https://github.com/greggigon/my-personal-kanban-server" target="blank">https://github.com/greggigon/my-personal-kanban-server</a>. Just select the option and provide URL for the Local Cloud service <strong>Cloud -> Setup -> Use own Local Cloud</strong>.
                        </small>
                    </p>
                </div>
                <div class="modal-footer">
                    <small class="pull-left">You can open this help anytime by pressing <abbr title="Ctrl-Shift-h when browser window is selected">Ctrl-Shift-h</abbr> on keyboard.</small>
                    <button class="btn btn-default" type="button" ng-click="close()">Close</button>
                </div>
            </script>
            </section>
            </div>
            <footer>

            </footer>







		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->


<?php
//get_sidebar();
get_footer();
