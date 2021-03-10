# Buto-Plugin-FolderList
List files from a folder. 

## Widgets

### list
```
type: widget
data:
  plugin: folder/list
  method: list
  data: yml:/theme/[theme]/config/help_download.yml
```

### download
On a page with no other content.
```
type: widget
data:
  plugin: folder/list
  method: download
  data: yml:/theme/[theme]/config/help_download.yml
```

### tree_instructions
One could use this widget on page where to manage files.
```
type: widget
data:
  plugin: folder/list
  method: tree_instructions
```

## Data
Param url is where page with widget download is.
```
path: /../buto_data/theme/[theme]/help
public: false
url: /d/help_upload_file
role:
  - webmaster
  - webadmin
success: PluginWfAjax.update('modal_help_webadmin_body');
max_size: 20
type:
  - image/jpeg
  - image/png
  - application/pdf
name:
  - '*.jpg'
  - '*.png'
  - '*.pdf'
```

### Tree view
Add this param to display tree view instead of a list.
```
tree: true
```
(sort value)_(tree level)@One.pdf.
```
001_0@One.pdf
002_1@One.pdf
003_1@Two.pdf
004_2@One.pdf
```
The tree will look like this.
```
One
  One
  Two
    One
```
