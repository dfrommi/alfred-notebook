import datetime
import urllib
import re

from pyparsing import *


class CategoryCommand:
    def __init__(self, workflow, github):
        self.wf = workflow
        self.gh = github

    def execute(self, query):
        categories = self.wf.cached_data('categories', self.getCatergories, max_age=30)

        if query:
            categories = self.wf.filter(query, categories, lambda c: c['name'])

        for category in categories:
            categoryName = category['name']

            self.wf.add_item(categoryName,
                             uid=category['sha'],
                             arg=categoryName,
                             valid=True,
                             autocomplete=categoryName,
                             icon='note-taking_icon.jpg')

        return len(categories) > 0

    def getCatergories(self):
        return [i for i in self.gh.ls('/') if i['type'] == 'dir' and i['name'] != 'resources']
