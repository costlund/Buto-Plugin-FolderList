# Buto-Plugin-FolderList
List files from a folder. 

## Widget list
```
type: widget
data:
  plugin: folder/list
  method: list
  data: yml:/theme/[theme]/config/help_download.yml
```

## Widget download
On a page with no other content.
```
type: widget
data:
  plugin: folder/list
  method: download
  data: yml:/theme/[theme]/config/help_download.yml
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
Files must be named like this. Separator is underscore (_).
```
One.pdf
One_One.pdf
One_Two.pdf
One_Two_One.pdf
```
The tree will look like this.
```
One
  One
  Two
    One
```
