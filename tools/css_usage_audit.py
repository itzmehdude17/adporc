import re
import os
import json

root = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
css_path = os.path.join(root, 'assets', 'css', 'style.css')

def extract_selectors(css_text):
    classes = set()
    ids = set()
    # capture selectors blocks before '{'
    for m in re.finditer(r'([^{}]+)\{', css_text):
        sel_group = m.group(1)
        parts = [s.strip() for s in sel_group.split(',') if s.strip()]
        for p in parts:
            # find simple .class and #id tokens in the selector
            for cm in re.finditer(r'[.#]([A-Za-z0-9_-]+)', p):
                name = cm.group(1)
                if p[cm.start()] == '.':
                    classes.add(name)
                else:
                    ids.add(name)
    return sorted(classes), sorted(ids)


def file_matches_any(file_text, name, is_class=True):
    # check common HTML/JS patterns
    patterns = []
    if is_class:
        patterns += [
            r'class=["\\'][^"\']*\\b' + re.escape(name) + r'\\b[^"\']*["\"]',
            r'className\s*=\s*["\\\']' + re.escape(name) + r'\b',
            r'classList\.(?:add|contains|remove)\(\s*["\\']' + re.escape(name) + r'["\\']',
            r'querySelector(All)?\(\s*["\\']\.' + re.escape(name) + r'["\\']',
            r'getElementsByClassName\(\s*["\\']' + re.escape(name) + r'["\\']'
        ]
    else:
        patterns += [
            r'id=["\\'][^"\']*\b' + re.escape(name) + r'\b[^"\']*["\"]',
            r'getElementById\(\s*["\\']' + re.escape(name) + r'["\\']',
            r'querySelector(All)?\(\s*["\\']#' + re.escape(name) + r'["\\']'
        ]
    for pat in patterns:
        if re.search(pat, file_text):
            return True
    return False


def scan_usage(classes, ids):
    usage = { 'classes': {c: [] for c in classes}, 'ids': {i: [] for i in ids} }
    exts = ('.html', '.htm', '.js')
    for dirpath, dirnames, filenames in os.walk(root):
        for fn in filenames:
            if fn.lower().endswith(exts):
                fp = os.path.join(dirpath, fn)
                try:
                    with open(fp, 'r', encoding='utf-8', errors='ignore') as f:
                        text = f.read()
                except Exception:
                    continue
                for c in classes:
                    if file_matches_any(text, c, is_class=True):
                        usage['classes'][c].append(os.path.relpath(fp, root))
                for i in ids:
                    if file_matches_any(text, i, is_class=False):
                        usage['ids'][i].append(os.path.relpath(fp, root))
    return usage


def main():
    if not os.path.exists(css_path):
        print('style.css not found at', css_path)
        return
    with open(css_path, 'r', encoding='utf-8', errors='ignore') as f:
        css_text = f.read()
    classes, ids = extract_selectors(css_text)
    print(f'Found {len(classes)} class selectors and {len(ids)} id selectors in style.css')
    usage = scan_usage(classes, ids)
    unused = {
        'classes': [c for c in classes if not usage['classes'][c]],
        'ids': [i for i in ids if not usage['ids'][i]]
    }
    report = {
        'root': root,
        'css_path': os.path.relpath(css_path, root),
        'total_classes': len(classes),
        'total_ids': len(ids),
        'usage': usage,
        'unused': unused
    }
    out_path = os.path.join(root, 'tools', 'css-usage-report.json')
    with open(out_path, 'w', encoding='utf-8') as fo:
        json.dump(report, fo, indent=2)
    print('\nScan complete.')
    print(f"Unused classes: {len(unused['classes'])}")
    print('\n'.join(unused['classes'][:200]))
    print('\nUnused ids: ', len(unused['ids']))
    print('\n'.join(unused['ids'][:200]))
    print('\nReport written to', out_path)

if __name__ == '__main__':
    main()
