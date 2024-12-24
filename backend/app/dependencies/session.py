from fastapi import Request, Depends
from typing import Annotated

def get_session(request: Request):
    return request.session

SessionDep = Annotated[dict, Depends(get_session)] 