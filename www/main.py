from flask import Flask, render_template
from mp1 import gerar_link_pagamento

def homepage():
    link_iniciar_pagamento = gerar_link_pagamento()
    return render_template ("index.html", link_pagamento =link_iniciar_pagamento)