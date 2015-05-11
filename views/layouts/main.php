<?php
use \admin\Module as Admin;

$user = Admin::getAdminUserData();
$gravatar = 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($user->email))).'?d='.urlencode('http://www.zephir.ch/files/rocky_460px_bw.jpg').'&s=40';

$this->beginPage()
?>
<!DOCTYPE html>
<html ng-app="zaa" ng-controller="HtmlController">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title>LUYA</title>
    <?php $this->head() ?>
    <script>
        var authToken = '<?=$user->getAuthToken();?>';
    </script>
</head>
<body>
<?php $this->beginBody() ?>
<script type="text/ng-template" id="storageFileUpload">
        <div>
            <table border="1">
                <tr>
                    <td style="padding:20px;">
            <table>
                <tr><td>Datei Auswahl:</td><td><input file-model="myFile" type="file" /></td><td><button ng-click="push()" type="button">Datei Hochladen</button></td></tr>
                <tr><td colspan="3"><a ng-show="filesrc" target="_blank" ng-href="{{filesrc}}">Datei: {{filesrc}}</a></td></tr>
            </table>
                    </td>
                    <td style="background-color:#999;">Upload - or - Select</td>
                    <td style="padding:20px;">
                        <button type="button" ng-click="toggleFileManager()">Show File manager</button>
                        <modal ng-show="showFileManager"><storage-file-manager ng-model="$parent.ngModel"/></div></modal>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">fileId: {{ ngModel }}</td>
                </tr>
            </table>
        </div>
    </script>

<script type="text/ng-template" id="storageImageUpload">
        <table border="1">
            <tr><td>Filter:</td><td> <select name="filterId" ng-model="filterId"><option value="0">Kein Filter</option><option ng-repeat="item in filters" value="{{ item.id }}">{{ item.name }} ({{ item.identifier }})</option></select></td></tr>
            <tr><td>Datei:</td><td><storage-file-upload ng-model="fileId"></storage-file-upload></td></tr>
            <tr><td></td><td><button ng-click="push2()" type="button">Hochladen</button></td></tr>
            <tr>
                <td>Bild</td>
                <td>
                    <div ng-show="imagesrc"><a href="{{imagesrc}}" target="_blank"><img ng-show="imagesrc" ng-src="{{imagesrc}}" height="100" /></a></div>
                    <div ng-show="!imagesrc"><p>Sie müssen zuerst einen Filter und eine Datei auswählen und danach auf <strong>Hochladen</strong> klicken um das Bild anzuzeigen.</div>
                </td>
            </tr>
        </table>
    </script>

<script type="text/ng-template" id="storageFileManager">
        <div style="border:1px solid #333; background-color:#e1e1e1; padding:10px;" ng-if="!isHidden">
            <ul style="padding:0px; margin:0px;">
                <li style="display:inline-block;" ng-repeat="crumb in breadcrumbs"><button type="button" ng-click="loadFolder(crumb.id)">{{ crumb.name }}</button> <i class="fa fa-caret-right"></i></li>
            </ul>
            <table border="1">
            <tr>
                <td><i class="fa fa-folder-o"></i></td><td colspan="4"><input type="text" ng-model="newFolderName" /><button type="button" ng-click="createNewFolder(newFolderName)"><i class="fa fa-plus-circle"></i> Ordner erstellen</button></td>
            </tr>

            <tr ng-repeat="folder in folders">
                <td><i class="fa fa-folder"></i></td><td colspan="3"><button ng-click="loadFolder(folder.id)" type="button">{{ folder.name }}</button></td><td><button ng-show="hasSelection()" ng-click="moveFilesTo(folder)" type="button"><i class="fa fa-long-arrow-left"></i> move to</button></div>
            </tr>

            <tr ng-repeat="file in files" ng-click="toggleSelection(file)" ng-class="{'is-active' : inSelection(file)}">
                <td>{{ file.id }} <span ng-if="inSelection(file)">SELECTED</span></td><td><strong>{{file.name_original}}</strong></td><td>{{ file.extension }}</td><td><button type="button" ng-if="allowSelection == true" ng-click="selectFile(file)"><i class="fa fa-check-circle" /></button></td>
            </tr>
            </table>
        </div>
    </script>
<div class="navbar-fixed">
    <nav>
        <div class="nav-wrapper light-blue darken-3" ng-controller="MenuController">
            <ul id="mainnav" class="left hide-on-med-and-down">
                <li ng-repeat="item in items"><a ng-click="click(item, $event)"><i class="{{item.icon}} left"></i> {{item.alias}}</a></li>
            </ul>
            <ul class="right hide-on-med-and-down">
                <li><a href="sass.html"><i class="mdi-action-search"></i></a></li>
            </ul>
        </div>
    </nav>
</div>
<div ui-view></div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>