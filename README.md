# Github Notebook workflow

This is an Alfred Workflow for notebooks maintained on Github.

Notes are plain *Markdown* files in a Github repository. They have to be stored in subdirectories, representing the note's category.
The special directory `resources` is used for additional files like images or binary files and is not used as a category.

All notes have a header with creation date and tags.

The special file `bookmarks.md` in top level directory is used to maintain a bookmark list.

## Configuration
### Respository (mandatory)
Github notebook repository has to be set before workflow can be used.

```
nb_conf repo <user>/<reponame>
```

### Access token (optional)
Github access token is required to access private repositories and is not required if notebook repository is public.

```
nb_conf token <access_token>
```

## Search notes
The workflow can search for notes containing text.

```
nb <search_text>
```

Search results can be opened from Alred in default browser.

## Create notes
Note creation can be triggered from Alfred workflow
```
nb + <note_name>
```
A list of categories is shown in Alfred. When seleting the category, Github is opened showing *new file* page of the category. Filename is prefilled as well as the note header with current date and empty tag list.

## Open bookmarks
Bookmarks file can be opened from workflow.
```
bm
```

## Add new bookmark
New bookmarks can be added from workflow. While the page to be bookmarked is opened in *Chrome* or *Safari*, execute
```
bm+
```
The bookmarks file is openend in editing mode in browser and the link to the page in available in clipboard. Just paste is to the desired section.

The shortcut `Shift`+`Cmd`+`D` is doing the same.

	
