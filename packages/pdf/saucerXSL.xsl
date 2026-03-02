<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:fo="http://xmlns.com/fonts/">
	<xsl:output method="xhtml" encoding="UTF-8" indent="no"/>
	<xsl:strip-space elements="qcontent answer explanation" />
	<xsl:param name="siteName" />
	<xsl:param name="examVendor" />
	<xsl:param name="examCode" />
	<xsl:param name="header" />
	<xsl:param name="footer" />	
	<xsl:param name="css" />	
	<xsl:template match="/">	
		<html>
			<head>
				<title><xsl:value-of select="concat($siteName,'  ', $examVendor, ' - ', $examCode)"/></title>				
				<link rel="stylesheet" type="text/css" href="{$css}" media="screen,print" ></link>
			</head>
			<body>
				<!--<div class="header"><xsl:value-of select="concat($header,' : ', $examVendor, ' - ', $examCode)"></xsl:value-of></div>-->
				
				<div class="header"><span class="headerMsg"><xsl:value-of select="$header"/></span><span class="headerExamMsg"><xsl:value-of select="concat( $examVendor, ' - ', $examCode)"/> </span></div>
				
				<div class="footer"><span class="footerMsg"><xsl:value-of select="$footer"></xsl:value-of></span> <span class="footerPageNumbersCover"><span class="footerPageNumbers"><span id="pagenumber"/> of <span id="pagecount"/></span></span></div>
				<xsl:choose>
					<xsl:when test="count(/exam/topic) &gt;= 1">
						<div id="topics">
							<div class="topicBreakdown">Exam Topic Breakdown</div>
							<table>
								<thead>
									<tr>
										<th>Exam Topic</th>
										<th>Number of Questions</th>
									</tr>					
								</thead>
								<tbody>
									<xsl:for-each select="/exam/topic">
										<xsl:variable name="nTopicId" select="@nTopicId"/>
										<xsl:variable name="sTopicDesc" select="@sDescription"/>
										<xsl:variable name="nTopicNum" select="@nTopicNum"/>
										<tr>						
											<td><a href="#topic-{$nTopicNum}"><xsl:value-of select="concat('Topic ', $nTopicNum,' : ',$sTopicDesc)"/></a></td>
											<td><xsl:value-of select="count(../question[@nTopicId = $nTopicId])"/></td>	
										</tr>									
									</xsl:for-each>
									<tr>						
										<td><xsl:text>TOTAL</xsl:text></td>
										<td><xsl:value-of select="count(//question)"/></td>	
									</tr>
								</tbody>				
							</table>
						</div>								
						<xsl:for-each select="/exam/topic">
							<xsl:variable name="nTopicId" select="@nTopicId"/>
							<xsl:variable name="sTopicDesc" select="@sDescription"/>
							<xsl:variable name="nTopicNum" select="@nTopicNum"/>
							<div class="topic">
								<div class="topicInfo">
									<a name="topic-{$nTopicNum}">
										<h2>Topic <xsl:value-of select="$nTopicNum"/>
											<xsl:if test="$sTopicDesc">, <xsl:value-of select="$sTopicDesc"/></xsl:if></h2>
									</a>
								</div>
								<div class="topicDesc">
									<xsl:apply-templates/>
								</div>
							</div>
							<xsl:for-each select="../question[@nTopicId = $nTopicId]">			
								<xsl:variable name="nQuestionNum" select="@nQuestionNum + 1"/>
								<xsl:variable name="sQuestionType" select="@type"/>
								<div class="question">
									<div class="questionNumber">Question #:<xsl:value-of select="$nQuestionNum"/> <!-- - <xsl:if test="$sQuestionType"><xsl:value-of select="$sQuestionType"/></xsl:if> --> - <a href="#topic-{$nTopicNum}">(Exam Topic <xsl:value-of select="$nTopicNum"/>)</a></div>
									<xsl:for-each select="qcontent"><xsl:apply-templates/></xsl:for-each>			
									<div class="answer">
										<xsl:choose>
											<xsl:when test="count(answer) &gt; 1">
												<ul>								
													<xsl:for-each select="answer">
														<li style="list-style-type:upper-alpha">
															<xsl:apply-templates/>
														</li>									
													</xsl:for-each>								
												</ul>							
												<div class="correctAnswers">
													<span class="correctAnswerLabel">Answer: </span>
													<xsl:for-each select="answer">									
														<xsl:if test=".[@correct = 1]">
															<xsl:value-of select="concat(codepoints-to-string(sum(64 + position())), '  ')"></xsl:value-of>
														</xsl:if>
													</xsl:for-each>	
												</div>
											</xsl:when>
											<xsl:otherwise>
												<xsl:choose>
													<xsl:when test="(count(answer) = 1) and (lower-case($sQuestionType) != 'correct text')  and contains(answer[@correct = 1]/p/s/text(), '&lt;map&gt;')">
														<div class="correctAnswers">
															<span class="correctAnswerLabel">Answer: </span>
															<div class="answerImage">
																<img>
																	<xsl:attribute name="src"><xsl:value-of select="concat('answer-images/',$nQuestionNum,'.png')"/></xsl:attribute>
																</img>
															</div>
														</div>																					
													</xsl:when>
													<xsl:otherwise>
														<xsl:for-each select="answer">
															<xsl:apply-templates/>
														</xsl:for-each>
													</xsl:otherwise>
												</xsl:choose>							
											</xsl:otherwise>
										</xsl:choose>					
									</div>
									<div class="explanation">		
										<xsl:variable name="expLength">
											<!--xsl:value-of select="string-length(explanation[1]/p[1]/s[1]/text())"/-->
											<xsl:for-each select="explanation/p">
												<xsl:for-each select="s">
													<xsl:value-of select="string-length(./text())"/>
												</xsl:for-each>	
												<xsl:for-each select="image">
													<xsl:value-of select="string-length(@nImageId)"/>
												</xsl:for-each>												
											</xsl:for-each>
										</xsl:variable>
										<xsl:if test="number($expLength) > 0">
											<h3>Explanation</h3>	
										</xsl:if>
										<xsl:if test="count(explanation//map) = 0">
											<xsl:for-each select="explanation">						
												<xsl:apply-templates/>
											</xsl:for-each>
										</xsl:if>
										<xsl:for-each select="qextra">
											<xsl:apply-templates/>
										</xsl:for-each>
									</div>
								</div>
							</xsl:for-each>
						</xsl:for-each>
					</xsl:when>
					<xsl:otherwise>
						<xsl:for-each select="//question">			
							<xsl:variable name="nQuestionNum" select="@nQuestionNum + 1"/>
							<xsl:variable name="sQuestionType" select="@type"/>
							<div class="question">
								<div class="questionNumber">Question #:<xsl:value-of select="$nQuestionNum"/></div>
								<xsl:for-each select="qcontent"><xsl:apply-templates/></xsl:for-each>			
								<div class="answer">
									<xsl:choose>
										<xsl:when test="count(answer) &gt; 1">
											<ul>								
												<xsl:for-each select="answer">
													<li style="list-style-type:upper-alpha">
														<xsl:apply-templates/>
													</li>									
												</xsl:for-each>								
											</ul>							
											<div class="correctAnswers">
												<span class="correctAnswerLabel">Answer: </span>
												<xsl:for-each select="answer">									
													<xsl:if test=".[@correct = 1]">
														<xsl:value-of select="concat(codepoints-to-string(sum(64 + position())), '  ')"></xsl:value-of>
													</xsl:if>
												</xsl:for-each>	
											</div>
										</xsl:when>
										<xsl:otherwise>
											<xsl:choose>
												<xsl:when test="(count(answer) = 1) and (lower-case($sQuestionType) != 'correct text')  and contains(answer[@correct = 1]/p/s/text(), '&lt;map&gt;')">
													<div class="correctAnswers">
														<span class="correctAnswerLabel">Answer: </span>
														<div class="answerImage">
															<img>
																<xsl:attribute name="src"><xsl:value-of select="concat('answer-images/',$nQuestionNum,'.png')"/></xsl:attribute>
															</img>
														</div>
													</div>																					
												</xsl:when>
												<xsl:otherwise>
													<xsl:for-each select="answer">
														<xsl:apply-templates/>
													</xsl:for-each>
												</xsl:otherwise>
											</xsl:choose>							
										</xsl:otherwise>
									</xsl:choose>					
								</div>
								<div class="explanation">		
									<xsl:variable name="expLength">
										<!--xsl:value-of select="string-length(explanation[1]/p[1]/s[1]/text())"/-->
										<xsl:for-each select="explanation/p">
											<xsl:for-each select="s">
												<xsl:value-of select="string-length(./text())"/>
											</xsl:for-each>										
										</xsl:for-each>
									</xsl:variable>
									<xsl:if test="number($expLength) > 0">
										<h3>Explanation</h3>	
									</xsl:if>
									<xsl:if test="count(explanation//map) = 0">
										<xsl:for-each select="explanation">						
											<xsl:apply-templates/>
										</xsl:for-each>
									</xsl:if>
								</div>
							</div>
						</xsl:for-each>
					</xsl:otherwise>
				</xsl:choose>
			</body>
		</html>
	</xsl:template>
	
	<xsl:template match="p">
		<p>
			<xsl:for-each select="s">
				<xsl:choose>
					<xsl:when test=".[@fo:font-weight='bold']">
						<b><xsl:value-of select="."/></b>
					</xsl:when>
					<xsl:when test=".[@fo:font-style='italic']">
						<i><xsl:value-of select="."/></i>
					</xsl:when>
					<xsl:otherwise>
						<xsl:choose>				
							<xsl:when test="contains(.,'http://')">
								<xsl:choose>
									<xsl:when test="contains(., ' ')">
										<xsl:variable name="actionUrl" select="substring-before(., ' ')"/>
										<a href="{$actionUrl}"><xsl:value-of select="$actionUrl"/></a>										
										<p>
											<xsl:value-of select="substring-after(., ' ')"/>
										</p>
									</xsl:when>
									<xsl:otherwise>
										<xsl:variable name="actionUrl" select="."/>
										<a href="{$actionUrl}"><xsl:value-of select="$actionUrl"/></a>
									</xsl:otherwise>
								</xsl:choose>								
							</xsl:when>		
							<xsl:when test="contains(.,'\Kamran\')">							
							</xsl:when>
							<xsl:otherwise>
								<xsl:value-of select="."/>
							</xsl:otherwise>
						</xsl:choose>		
					</xsl:otherwise>
				</xsl:choose>
			</xsl:for-each>	
			<xsl:for-each select="image">
				<img>
					<xsl:attribute name="src"><xsl:value-of select="concat('images/',@nImageId,@sExt)"/></xsl:attribute>
				</img>
			</xsl:for-each>
		</p>
	</xsl:template>
	
	<xsl:template match="image">
		<img>
			<xsl:attribute name="src"><xsl:value-of select="concat('images/',@nImageId,@sExt)"/></xsl:attribute>
		</img>
	</xsl:template>
	
	<xsl:template match="list">
		<ul>
			<xsl:for-each select="listitem">
				<li>
					<xsl:apply-templates/>
				</li>
			</xsl:for-each>
		</ul>
	</xsl:template>
	
</xsl:stylesheet>