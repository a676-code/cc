# functionName := 
#   proc( parameterDeclarations ) :: returnType;
#   description shortDescription;
#   option optionSequence;
#   local localVariableDeclarations;
#   global globalVariableDeclarations;
#   statementSequence
# end proc
f := proc(x::posint)::posint;
    if x = 1 then
        return 1;
    else
        if x mod 2 = 1 then
            return f(3*x + 1):
        else
            return f(x / 2):
        end if
    end if
end proc:
f(27);