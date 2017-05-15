import json
import fabric.api as fab

def merge(a, b, path=None, update=True):
    "http://stackoverflow.com/questions/7204805/python-dictionaries-of-dictionaries-merge"
    "merges b into a"
    if path is None:
        path = []
    for key in b:
        if key in a:
            if isinstance(a[key], dict) and isinstance(b[key], dict):
                merge(a[key], b[key], path + [str(key)])
            elif a[key] == b[key]:
                pass  # same leaf value
            elif isinstance(a[key], list) and isinstance(b[key], list):
                for idx, val in enumerate(b[key]):
                    a[key][idx] = merge(a[key][idx], b[key][idx], path + [str(key), str(idx)], update=update)
            elif update:
                a[key] = b[key]
            else:
                raise Exception('Conflict at %s' % '.'.join(path + [str(key)]))
        else:
            a[key] = b[key]
    return a


def remove(d, keys):
    return {k: v for k, v in d.items() if k not in keys}


def pprint(x, print_fn=fab.puts):
    print_fn(json.dumps(x, indent=2))
