#!/usr/bin/python
# encoding: utf-8

import sys
import argparse

from workflow import Workflow
from notebook import GitHub
from notebook.command import *

def main(wf):
    parser = getCommandLineParser()
    args = parser.parse_args(wf.args)

    gh = GitHub(args.repo, args.token)

    commands = []

    if args.command == "categories":
        commands.append(CategoryCommand(wf, gh))
    elif args.command == "search":
        commands.append(SearchCommand(wf, gh))
    else:
        commands.append(DefaultCommand(wf, gh))

    for command in commands:
        if(command.execute(args.query)):
            break

    wf.send_feedback()


def getCommandLineParser():
    parser = argparse.ArgumentParser(description='Github Notebook workflow helper')
    parser.add_argument('command')
    parser.add_argument('query', nargs='?')
    parser.add_argument('-t', '--token', help='Access token', required=False)
    parser.add_argument('-r', '--repo', help='Repository', required=True)
    return parser

if __name__ == '__main__':
    wf = Workflow()
    sys.exit(wf.run(main))
