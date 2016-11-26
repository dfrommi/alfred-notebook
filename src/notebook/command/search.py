import os

from workflow import ICON_WARNING


class SearchCommand:
    def __init__(self, workflow, github):
        self.wf = workflow
        self.gh = github

    def execute(self, query):
        if len(query) <= 2:
            return False

        items = self.gh.search(query)

        for item in items:
            category = os.path.dirname(item['path'])
            noteName = os.path.splitext(item['name'])[0]
            self.wf.add_item(noteName,
                             category,
                             uid=item['sha'],
                             arg=self.gh.getUrlOfItem(item),
                             valid=True,
                             icon='note-taking_icon.jpg',
                             quicklookurl=self.gh.getUrlOfItem(item))

        if not items:
            self.wf.add_item('No posts found', icon=ICON_WARNING)

        return True
